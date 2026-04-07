<?php

namespace App\Services;

use App\Models\Url;
use GuzzleHttp\Client;

class NGeniusService
{
    protected $client;

    protected $apiUrl;

    protected $apiKey;

    protected $outletRefId;

    public function __construct($apiKey = null, $outletRefId = null)
    {
        $url = Url::query()->first();
        $this->client = new Client();
        $this->apiUrl = $url->payment_base_url;
        $this->apiKey = $apiKey ?? $url->api_key;
        $this->outletRefId = $outletRefId ?? $url->reference_key;
    }

    public function getAccessToken()
    {
        $response = $this->client->post($this->apiUrl . '/identity/auth/access-token', [
            'headers' => [
                'Authorization' => 'Basic ' . $this->apiKey,
                'Content-Type' => 'application/vnd.ni-identity.v1+json',
                'Accept' => 'application/vnd.ni-identity.v1+json',
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        return $data['access_token'];
    }

    public function initiatePayment($amount, $currency, $redirectUrl, $cancelUrl, $email)
    {
        $accessToken = $this->getAccessToken();

        $response = $this->client->post($this->apiUrl . '/transactions/outlets/' . $this->outletRefId . '/orders', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/vnd.ni-payment.v2+json',
                'Accept' => 'application/vnd.ni-payment.v2+json',
            ],
            'json' => [
                'action' => 'PURCHASE',
                'amount' => [
                    'currencyCode' => $currency,
                    'value' => $amount * 100,
                ],
                'merchantAttributes' => [
                    'redirectUrl' => $redirectUrl,
                    'skipConfirmationPage' => true,
                    'cancelUrl' => $cancelUrl,
                ],
                'emailAddress' => $email,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    public function getPaymentStatus($orderReference)
    {
        $accessToken = $this->getAccessToken();

        $response = $this->client->get($this->apiUrl . '/transactions/outlets/' . $this->outletRefId . '/orders/' . $orderReference, [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/vnd.ni-payment.v2+json',
                'Accept' => 'application/vnd.ni-payment.v2+json',
            ],
        ]);

        return json_decode($response->getBody(), true);
    }
}
