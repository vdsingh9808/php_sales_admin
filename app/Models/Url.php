<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $authentication_url
 * @property string $server_url
 * @property string $payment_base_url
 * @property string $api_key
 * @property string $reference_key
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Url newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Url newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Url query()
 * @method static \Illuminate\Database\Eloquent\Builder|Url whereApiKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Url whereAuthenticationUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Url whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Url whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Url wherePaymentBaseUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Url whereReferenceKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Url whereServerUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Url whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Url extends Model
{
    use HasFactory;

    protected $table = 'urls';

    protected $fillable = [
        'authentication_url',
        'server_url',
        'payment_base_url',
        'api_key',
        'reference_key',
    ];
}
