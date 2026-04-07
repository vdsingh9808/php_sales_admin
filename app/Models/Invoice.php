<?php

namespace App\Models;

use App\Enums\ReceiptDistributionStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $transaction_id
 * @property string $invoice_id
 * @property float $invoice_amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice query()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereInvoiceAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';

    protected $fillable = [
        'transaction_id',
        'invoice_id',
        'invoice_amount',
        'customer_type' ,
        'customer_type_value' ,
        'legal_entity' ,
        'unit_rental' ,
        'profile_code' ,
        'transaction_account' ,
        'property_unit' ,
        'property' ,
        'property_transaction_category' ,
        'invoice_name' ,
        'invoice_line_id',
        'payment_schedule_value',
        'status_updated_in_cms',
        'receipt_distribution_status'
    ];

    protected $casts = [
        'status_updated_in_cms' => 'boolean',
        'receipt_distribution_status' => ReceiptDistributionStatusEnum::class
    ];
}
