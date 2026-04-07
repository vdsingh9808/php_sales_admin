<?php

namespace App\Http\Controllers\Api;

use App\Enums\ApiProgressLevelEnum;
use App\Enums\ReceiptDistributionStatusEnum;
use App\Enums\ReceiptProcessStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Entity;
use App\Models\Invoice;
use App\Models\Transaction;
use App\Models\Url;
use App\Services\NGeniusService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    protected $ngeniusService;

    public function __construct(NGeniusService $ngeniusService)
    {
        $this->ngeniusService = $ngeniusService;
    }

    public function processPayment(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'invoice_line_ids' => 'required|array',
            'invoice_line_ids.*' => 'string',
            'entity_id' => 'nullable|string',
            'redirect_url' => 'required|url',
            'cancel_url' => 'required|url',
            'email' => 'nullable|email',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $apiKey = null;
        $outletRefId = null;

        if (!empty($request->input('entity_id'))) {
            $entity = Entity::query()
                ->where('entity_id', $request->input('entity_id'))
                ->first();

            if ($entity) {
                $apiKey = $entity->api_key;
                $outletRefId = $entity->reference_key;
            }
        }
        
        $invoiceLineIds = $request->input('invoice_line_ids');
        $totalAmount = 0;

        $transaction = Transaction::create([
            'status' => 'DRAFT',
        ]);

        foreach ($invoiceLineIds as $invoiceLineId) {
            $invoiceDetails = $this->fetchInvoiceDetails($invoiceLineId);
            
            $data = $invoiceDetails['value'][0] ?? [];
            
            Invoice::create([
                'invoice_id' => $data['_lvt_invoice_value'],
                'transaction_id' => $transaction->id,
                'invoice_amount' => $invoiceDetails['value'][0]['lvt_totalamount'],
                'invoice_name' => $data['lvt_name'] ?? null,
                'customer_type'  => $data['_lvt_customer_value@Microsoft.Dynamics.CRM.lookuplogicalname'] ?? null,
                'customer_type_value' => $data['_lvt_customer_value'] ?? null,
                'legal_entity' => $data['_lvt_legalentity_value'] ?? null,
                'unit_rental' => $data['_lvt_unitrental_value'] ?? null,
                'profile_code' => $data['_lvt_profilecode_value'] ?? null,
                'transaction_account' => $data['_lvt_transactionaccount_value'] ?? null,
                'property_unit' => $data['_lvt_propertyunit_value'] ?? null,
                // 'property' => $data['property.lvt_property'] ?? null,
                'property' => $data['_lvt_property_value'] ?? null,
                'property_transaction_category' => $data['_lvt_propertytransactioncategory_value'] ?? null,
                'invoice_line_id' => $invoiceLineId,
                'payment_schedule_value' => $data['_lvt_paymentscheduledetail_value'] ?? null,
            ]);

            $totalAmount += $invoiceDetails['value'][0]['lvt_totalamount'];
            /** @var NGeniusService $payment */
        }

        if ($apiKey && $outletRefId) {
            $payment = new NGeniusService($apiKey, $outletRefId);
        } else {
            $payment = app()->make(NGeniusService::class);
        }
        
        try {
            $redirectUrl = $request->input('redirect_url');
            $cancelUrl = $request->input('cancel_url');
            $email = $request->input('email');
            $order = $payment->initiatePayment($totalAmount, 'AED', $redirectUrl, $cancelUrl, $email);

            if (!isset($order['_embedded']['payment'][0]['orderReference'])) {
                throw new \Exception('Failed to retrieve order reference from payment response.');
            }

            $orderReference = $order['_embedded']['payment'][0]['orderReference'];

            $transaction->update([
                'order_reference' => $orderReference,
            ]);

            return response()->json(['payment_urls' => $order['_links']['payment']['href']]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function callback(Request $request, float $delay = 0): JsonResponse
    {
        try {
            if (!isset($request['order']['_embedded']['payment'][0]['orderReference']) ||
                !isset($request['order']['_embedded']['payment'][0]['state'])) {
                return response()->json(['error' => 'Invalid request data'], 400);
            }
           
            $orderReference = $request['order']['_embedded']['payment'][0]['orderReference'];
            $state = $request['order']['_embedded']['payment'][0]['state'];

            $transaction = Transaction::query()->where('order_reference', $orderReference)->first();
            if (!$transaction) {
                return response()->json(['error' => 'Transaction not found'], 404);
            }

            $transaction->status = $state;
            $transaction->save();

            $api_invoice = [] ;

            if(strtolower($state) == 'purchased') {

                $transaction->refresh() ;

                $invoices = $transaction->invoices ;

                foreach ($invoices as $invoice) {
                    if($invoice->status_updated_in_cms) {
                        continue ;
                    }

                    $payment_status = $this->updatePaymentStatus($invoice->invoice_line_id, $orderReference);

                    if(!empty($payment_status)) {
                        $invoice->status_updated_in_cms = 1 ;
                        $invoice->save();
                    }
                }

                $firstInvoice = $invoices->first() ;

                $total_invoice_amount = $invoices->sum('invoice_amount') ;                

                if(empty($transaction->receipt_processing_status) 
                    || $transaction->receipt_processing_status == ReceiptProcessStatusEnum::failed
                ) {

                    $transaction->receipt_processing_status = ReceiptProcessStatusEnum::processing; 
                    $transaction->save();

                    info("processing inside callback");
                    
                    $api_response_invoice = $this->fetchReceiptDetails($firstInvoice, $total_invoice_amount,"callback");
                    
                    $lvt_receipt_id = $api_response_invoice['lvt_receiptid'] ?? null ;
                    $lvt_id = $api_response_invoice['lvt_id'] ?? null ;
                    
                    if(empty($lvt_receipt_id)) {
                        $transaction->receipt_processing_status = ReceiptProcessStatusEnum::failed; 
                        $transaction->save();
                    }else{

                        $transaction->receipt_processing_status = ReceiptProcessStatusEnum::success ; 
                        $transaction->lvt_receipt_id = $lvt_receipt_id ;
                        $transaction->lvt_id = $lvt_id ;
                        $transaction->receipt_date = Carbon::now()->format('Y-m-d');
                        $transaction->save();
                    }   

                }else if($transaction->receipt_processing_status == ReceiptProcessStatusEnum::processing){

                    $this->delayProcess(
                        $request,
                        $transaction->receipt_processing_status,
                        'callback',
                        $delay
                    ) ;
                }

                $transaction->refresh() ;
                
                $invoices = $transaction->invoices ;

                info("transaction details inside callback",[
                    'transaction_receipt_status' => $transaction->receipt_processing_status ,
                    'receipt_distribution_status' => $transaction->receipt_distribution_status
                ]);

                if($transaction->receipt_processing_status == ReceiptProcessStatusEnum::success 
                    && empty($transaction->receipt_distribution_status)) 
                {

                    $transaction->receipt_distribution_status = ReceiptDistributionStatusEnum::processing ;
                    $transaction->save();

                    info("distributin inside callback",[
                        'invoice_ids' => $invoices->pluck('id')->toArray() ,
                        'receipt_id' => $transaction->lvt_receipt_id,
                        'lvt_id' => $transaction->lvt_id ,
                        'receipt_distribution_status' => $transaction->receipt_distribution_status ,
                        'receipt_processing_status' => $transaction->receipt_processing_status
                    ]);

                    foreach ($invoices as $invoice) {
        
                        $distribution = $this->receiptDistribution($invoice, $transaction, "callback");
                    
                        $lvt_distribution = $distribution['lvt_receiptdistributionid'] ?? null ;

                        if(!empty($lvt_distribution)) {
                            $invoice->receipt_distribution_id = $lvt_distribution ;
                            $invoice->save();
                        }
                    }

                    $transaction->receipt_distribution_status = ReceiptDistributionStatusEnum::success ;
                    $transaction->save();

                }else if($transaction->receipt_distribution_status == ReceiptDistributionStatusEnum::processing){
                    $this->delayProcess(
                        $request,
                        $transaction->receipt_distribution_status,
                        'callback',
                        $delay
                    ) ;
                }else{
                    foreach ($invoices as $invoice) {
                        $api_invoice[] = [
                            'invoice_id' => $invoice->invoice_id, 
                            "invoice_name" => $invoice->invoice_name,
                            "invoice_amount" => $invoice->invoice_amount,
                            'receipt_distribution_id' => $invoice->receipt_distribution_id,
                            'invoice_line_id' => $invoice->invoice_line_id ,
                            'lvt_id' => $transaction->lvt_id,
                            "receipt_id" => $transaction->lvt_receipt_id ,
                            'receipt_date' => $transaction->receipt_date,
                        ];
                    }
                }
            }

            return response()->json([
                'status' => $transaction->status,
                "data" => $api_invoice,
                'total_invoice_amount' => $total_invoice_amount ?? null,
                'order_reference' => $transaction->order_reference
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getPaymentStatus(Request $request, float $delay = 0): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'order_reference' => 'required|string|exists:transactions,order_reference',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $transaction = Transaction::query()->where('order_reference', $request->order_reference)->first();

        if (!$transaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }

        $orderReference = $request->input('order_reference');
        $payment = app()->make(NGeniusService::class);

        try {
            
            if(!$transaction->payment_status_updated){
                $statusResponse = $payment->getPaymentStatus($orderReference);
        
                $state = $statusResponse['_embedded']['payment'][0]['state'];
                $message = $statusResponse['_embedded']['payment'][0]['authResponse']['resultMessage'] ?? null;

                if ($transaction->status !== $state) {
                    $transaction->status = $state;
                    $transaction->payment_status_updated = 1 ;
                    $transaction->save();
                }
            }

            $invoices = $transaction->invoices ;

            foreach ($invoices as $invoice) {
                if($invoice->status_updated_in_cms) {
                    continue ;
                }
                
                $payment_status = $this->updatePaymentStatus($invoice->invoice_line_id, $orderReference);

                if(!empty($payment_status)) {
                    $invoice->status_updated_in_cms = 1 ;
                    $invoice->save();
                }
            }
            
            $api_invoice = [] ;

            if(strtolower($state) == 'purchased') {

                $transaction->refresh() ;

                $firstInvoice = $invoices->first() ;

                $total_invoice_amount = $invoices->sum('invoice_amount') ;
                
                if(empty($transaction->receipt_processing_status) || $transaction->receipt_processing_status == ReceiptProcessStatusEnum::failed) {

                    $transaction->receipt_processing_status = ReceiptProcessStatusEnum::processing; 
                    $transaction->save();

                    info("processing inside payment status");
                    
                    $api_response_invoice = $this->fetchReceiptDetails($firstInvoice, $total_invoice_amount,"callback");
                    
                    $lvt_receipt_id = $api_response_invoice['lvt_receiptid'] ?? null ;
                    $lvt_id = $api_response_invoice['lvt_id'] ?? null ;
                    
                    if(empty($lvt_receipt_id)) {
                        $transaction->receipt_processing_status = ReceiptProcessStatusEnum::failed; 
                        $transaction->save();
                    }else{

                        $transaction->receipt_processing_status = ReceiptProcessStatusEnum::success ; 
                        $transaction->lvt_receipt_id = $lvt_receipt_id ;
                        $transaction->lvt_id = $lvt_id ;
                        $transaction->receipt_date = Carbon::now()->format('Y-m-d');
                        $transaction->save();
                    }   

                }else if($transaction->receipt_processing_status == ReceiptProcessStatusEnum::processing){

                    $this->delayProcess(
                        $request,
                        $transaction->receipt_processing_status,
                        'getPaymentStatus',
                        $delay
                    ) ;
                }

                $transaction->refresh() ;

                $invoices = $transaction->invoices ;

                info("transaction details inside payment status",[
                    'transaction_receipt_status' => $transaction->receipt_processing_status ,
                    'receipt_distribution_status' => $transaction->receipt_distribution_status
                ]);

                if($transaction->receipt_processing_status == ReceiptProcessStatusEnum::success 
                    && empty($transaction->receipt_distribution_status)) 
                {

                    $transaction->receipt_distribution_status = ReceiptDistributionStatusEnum::processing ;
                    $transaction->save();

                    info("distributin inside payment status",[
                        'invoice_ids' => $invoices->pluck('id')->toArray(),
                        'receipt_id' => $transaction->lvt_receipt_id,
                        'lvt_id' => $transaction->lvt_id,
                        'receipt_distribution_status' => $invoice->receipt_distribution_status ,
                        'receipt_processing_status' => $transaction->receipt_processing_status
                    ]);

                    foreach ($invoices as $invoice) {
                                                            
                        $distribution = $this->receiptDistribution($invoice, $transaction, "payment_status");
                    
                        $lvt_distribution = $distribution['lvt_receiptdistributionid'] ?? null ;

                        if(!empty($lvt_distribution)) {
                            $invoice->receipt_distribution_id = $lvt_distribution ;
                            $invoice->save();
                        }
                    }

                    $transaction->receipt_distribution_status = ReceiptDistributionStatusEnum::success ;
                    $transaction->save();

                }else if($transaction->receipt_distribution_status == ReceiptDistributionStatusEnum::processing){

                    $this->delayProcess(
                        $request,
                        $transaction->receipt_distribution_status,
                        'getPaymentStatus',
                        $delay
                    ) ;
                }else{
                    foreach ($invoices as $invoice) {
                        $api_invoice[] = [
                            'invoice_id' => $invoice->invoice_id, 
                            "invoice_name" => $invoice->invoice_name,
                            "invoice_amount" => $invoice->invoice_amount,
                            'receipt_distribution_id' => $invoice->receipt_distribution_id,
                            'invoice_line_id' => $invoice->invoice_line_id ,
                            'lvt_id' => $transaction->lvt_id,
                            "receipt_id" => $transaction->lvt_receipt_id ,
                            'receipt_date' => $transaction->receipt_date,
                        ];
                    }
                }
                
            }

            return response()->json([
                'status' => $transaction->status, 
                'message' => $message , 
                "data" => $api_invoice ,
                "total_invoice_amount" => $total_invoice_amount ?? null,
                'order_reference' => $transaction->order_reference
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    public function delayProcess(
        Request $request,
        null|ReceiptProcessStatusEnum|ReceiptDistributionStatusEnum $currentState, 
        string $method ,
        float $delay = 10.0,
        float $maxDelay = 60.0, 
        float $increment = 5.0,
    ): void {

        info('delay process',[
            'delay' => $delay,
            'currentState' => $currentState,
            'method' => $method
        ]) ;
        
        if($delay >= $maxDelay) {
            throw new Exception('Payment status check failed', 408);
        }
        
        // $delayStates = [
        //     ApiProgressLevelEnum::fetching_receipt_details,
        //     ApiProgressLevelEnum::receipt_distribution 
        // ];
    
        // if (!in_array($currentState, $delayStates)) {
        //     return;
        // }

        if($delay == 0) {
            $delay = 5.0;
        }
    
        $delay = min($delay + $increment, $maxDelay);

        sleep($delay);
    
        $this->$method($request, $delay);
    }

    private function fetchInvoiceDetails($invoiceLineId)
    {
        try {
            $url = Cache::remember('auth_token', 3600, function () {
                return URL::query()->first();
            });

            $tokenResponse = Http::post($url->authentication_url);
            $authToken = $tokenResponse->json('access_token');

            // $apiUrl = $url->server_url . '/lvt_invoices';
            $apiUrl = $url->server_url.'/lvt_invoicelines' ;

            // $fetchXml = '<fetch version="1.0" output-format="xml-platform" mapping="logical" distinct="false">
            //     <entity name="lvt_invoice"> <attribute name="lvt_name" />
            //     <attribute name="lvt_unitrental" /><attribute name="lvt_propertytransactioncategory" />
            //     <attribute name="lvt_rentedsoldunit" /><attribute name="lvt_transactionaccount" />
            //     <attribute name="lvt_propertyunit" /><attribute name="lvt_customer" />
            //     <attribute name="lvt_customer" />
            //     <attribute name="lvt_invoiceid" /><attribute name="lvt_totalamount" />
            //     <attribute name="lvt_taxamount" /><attribute name="statuscode" />
            //     <attribute name="statecode" /><attribute name="lvt_legalentity" />
            //     <attribute name="lvt_invoicedate" /><attribute name="lvt_dueon" />
            //     <attribute name="transactioncurrencyid" />
            //     <attribute name="lvt_amountinv" />
            //     <attribute name="lvt_amount" /><attribute name="lvt_profilecode" />
            //     <order attribute="lvt_name" descending="false" />
            //     <filter type="and">
            //     <condition attribute="lvt_invoiceid" operator="eq" uiname="INV-04312" uitype="lvt_invoice" value="' . $invoiceId . '" />
            //     <condition attribute="statecode" operator="eq" value="0" />
            //     </filter> <link-entity name="lvt_propertyunit" from="lvt_propertyunitid" to="lvt_propertyunit" visible="false" link-type="outer" alias="property">
            //     <attribute name="lvt_property" />
            //     </link-entity>
            //     </entity>
            // </fetch>';

            $fetchXml = '<fetch version="1.0" output-format="xml-platform" mapping="logical" distinct="false">
            <entity name="lvt_invoiceline"><attribute name="lvt_invoicelineid" />
            <attribute name="lvt_name" /><attribute name="createdon" />
            <attribute name="lvt_unitrental" /><attribute name="lvt_transactionaccount" />
            <attribute name="lvt_totalamount" /><attribute name="lvt_taxamount" />
            <attribute name="lvt_rentedsoldunit" /><attribute name="lvt_propertyunit" />
            <attribute name="lvt_propertytransactioncategory" /> <attribute name="lvt_property" />
            <attribute name="lvt_profilecode" />
            <attribute name="lvt_pendingamount" />
            <attribute name="lvt_paymentscheduledetail" />
            <attribute name="lvt_legalentity" />
            <attribute name="lvt_id" />
            <attribute name="lvt_customer" />
            <attribute name="lvt_invoice" />
            <order attribute="lvt_name" descending="false" />
            <filter type="and">
            <condition attribute="lvt_invoicelineid" operator="eq" uitype="lvt_invoiceline" value="' . $invoiceLineId . '"/>
            <condition attribute="statecode" operator="eq" value="0" />
            </filter>
            </entity>
            </fetch>';

            $apiResponse = Http::withToken($authToken)->withHeaders([
                'Prefer' => "return=representation,odata.include-annotations=*"
            ])->get($apiUrl, ['fetchXml' => $fetchXml]);

            $invoices = $apiResponse->json();
            
            if (!empty($invoices)) {
                return $invoices;
            } else {
                Log::error('No invoice details found for invoice ID: ' . $invoiceLineId);

                return null;
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch invoice details: ' . $e->getMessage());

            return null;
        }
    }

    private function fetchReceiptDetails(Invoice $invoice, $total_amount = 0, string $calling_from = '')
    {
        try {
            
            $url = Cache::remember('auth_token', 3600, function () {
                return URL::query()->first();
            });

            $tokenResponse = Http::post($url->authentication_url);
            $authToken = $tokenResponse->json('access_token');

            $apiUrl = $url->server_url . 'lvt_receipts?$select=lvt_id';

            $customer_type = match ($invoice->customer_type) {
                'contact' => 'contacts',
                'account' => 'accounts'
            };

            $invoice_amount = $total_amount != 0 ? $total_amount : $invoice->invoice_amount ;

            $params = [
                'lvt_Customer_account@odata.bind' => "/".$customer_type."(".$invoice->customer_type_value.")",
                'lvt_LegalEntity@odata.bind' => '/lvt_legalentities('.$invoice->legal_entity.')',
                "lvt_UnitRental@odata.bind" => "/lvt_unitrentals(". $invoice->unit_rental .")" ,
                "lvt_ProfileCode@odata.bind" => "/lvt_profilecodes(".$invoice->profile_code .")" ,
                "lvt_TransactionAccount@odata.bind"  => "/lvt_transactionaccounts(".$invoice->transaction_account.")" ,
                "lvt_PropertyUnit@odata.bind" =>  "/lvt_propertyunits(". $invoice->property_unit.")" ,
                "lvt_Property@odata.bind" =>  "/lvt_properties(".$invoice->property .")" ,
                "lvt_PropertyTransactionCategory@odata.bind" => "/lvt_transactioncategories(" .$invoice->property_transaction_category.")",
                "lvt_receiptamount" => $invoice_amount ,
                "lvt_transactiondate" => Carbon::now()->format('Y-m-d'),
                "lvt_receiptdate" => Carbon::now()->format('Y-m-d'),
                "lvt_PaymentScheduleDetail@odata.bind" => "/lvt_paymentscheduledetails(".$invoice->payment_schedule_value.")" ,
                "lvt_recordcreationmethod" => 553500001
            ];

            $apiResponse = Http::withToken($authToken)->withHeaders([
                'Prefer' => "return=representation,odata.include-annotations=*"
            ])->post($apiUrl, $params);

            $invoices = $apiResponse->json();

            info("receipt details params",[
                'params' => $params ,
                'response' => $invoices,
                'calling_from' => $calling_from
            ]);
           
            if (!empty($invoices)) {
                return $invoices;
            } else {
                Log::error('No Receipt details found for invoice ID: ' . $invoice->id);

                return null;
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch receipt details: ' . $e->getMessage());

            return null;
        }
    }

    private function receiptDistribution(Invoice $invoice, Transaction $transaction, string $calling_from)
    {
        try {

            $url = Cache::remember('auth_token', 3600, function () {
                return URL::query()->first();
            });

            $tokenResponse = Http::post($url->authentication_url);
            $authToken = $tokenResponse->json('access_token');

            $apiUrl = $url->server_url . 'lvt_receiptdistributions?$select=lvt_receiptdistributionid';

            $customer_type = match ($invoice->customer_type) {
                'contact' => 'contacts',
                'account' => 'accounts'
            };
            
            $params = [
                "lvt_LegalEntity@odata.bind" => '/lvt_legalentities('.$invoice->legal_entity.')',
                "lvt_UnitRental@odata.bind" => "/lvt_unitrentals(". $invoice->unit_rental .")" ,
                "lvt_ProfileCode@odata.bind" => "/lvt_profilecodes(".$invoice->profile_code .")" ,
                "lvt_TransactionAccount@odata.bind" => "/lvt_transactionaccounts(".$invoice->transaction_account.")" ,
                "lvt_PropertyTransactionCategory@odata.bind" => "/lvt_transactioncategories(" .$invoice->property_transaction_category.")",
                "lvt_Invoice@odata.bind" => "/lvt_invoices(".$invoice->invoice_id.")",
                "lvt_InvoiceLine@odata.bind" => "/lvt_invoicelines(".$invoice->invoice_line_id.")",
                "lvt_PaymentScheduleDetail@odata.bind" => "/lvt_paymentscheduledetails(".$invoice->payment_schedule_value.")",
                "lvt_Receipt@odata.bind" => "/lvt_receipts(".$transaction->lvt_receipt_id.")",
                "lvt_allocationamount" => $invoice->invoice_amount 
            ];
            
            if($customer_type == 'contacts') {
                $params['lvt_Customer_contact@odata.bind'] = "/".$customer_type."(".$invoice->customer_type_value.")" ;
            }else if($customer_type == 'accounts') {
                $params['lvt_Customer_account@odata.bind'] = "/".$customer_type."(".$invoice->customer_type_value.")" ;
            }

            $apiResponse = Http::withToken($authToken)->withHeaders([
                'Prefer' => "return=representation,odata.include-annotations=*"
            ])->post($apiUrl, $params);

            $invoices = $apiResponse->json();

            info("receipt distribution params",[
                'params' => $params ,
                'response' => $invoices,
                'api_url' => $apiUrl ,
                'calling_from' => $calling_from
            ]);
            
            if (!empty($invoices)) {
                return $invoices;
            } else {
                Log::error('No Receipt details found for invoice ID: ' . $invoice->id);

                return null;
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch receipt details: ' . $e->getMessage());

            return null;
        }
    }

    public function updatePaymentStatus(string $invoice_line_id, string $order_reference_id)
    {
        try {

            $url = Cache::remember('auth_token', 3600, function () {
                return URL::query()->first();
            });

            $tokenResponse = Http::post($url->authentication_url);
            $authToken = $tokenResponse->json('access_token');

            $apiUrl = $url->server_url . 'lvt_invoicelines('.$invoice_line_id.')';
            
            $params = [
                "lvt_portalpaymentstatus" => "918360000",
                "lvt_bankpaymentreferenceid" => "$order_reference_id"
            ];
            
            $apiResponse = Http::withToken($authToken)->withHeaders([
                'Prefer' => "return=representation,odata.include-annotations=*"
            ])->patch($apiUrl, $params);

            $invoices = $apiResponse->json();
           
            if (!empty($invoices)) {
                return $invoices;
            } else {
                Log::error('No Receipt details found for invoice ID: ' . $invoice_line_id);

                return null;
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch receipt details: ' . $e->getMessage());

            return null;
        }
    }

    
}
