<?php

namespace App\Models;

use App\Enums\ApiProgressLevelEnum;
use App\Enums\ReceiptDistributionStatusEnum;
use App\Enums\ReceiptProcessStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $status
 * @property string|null $order_reference
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereOrderReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = [
        'status',
        'order_reference',
        'lvt_receipt_id' ,
        'lvt_id' ,
        'receipt_date',
        'receipt_processing_status',
        'receipt_distribution_status'
    ];

    protected $casts = [
        'receipt_processing_status' => ReceiptProcessStatusEnum::class ,
        'receipt_distribution_status' => ReceiptDistributionStatusEnum::class
    ];

    public function invoices():HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}
