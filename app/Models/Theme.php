<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property int $id
 * @property string $name
 * @property string $general_heading
 * @property string $general_body
 * @property string $general_icons
 * @property string $general_background
 * @property string $table_heading
 * @property string $table_title
 * @property string $table_body
 * @property string $header_background
 * @property string $header_text
 * @property string $navbar_background
 * @property string $navbar_text
 * @property string $button_primary
 * @property string $button_secondary
 * @property bool $is_default
 * @property array|null $menu_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Entity|null $entity
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Theme newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Theme newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Theme query()
 * @method static \Illuminate\Database\Eloquent\Builder|Theme whereButtonPrimary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Theme whereButtonSecondary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Theme whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Theme whereGeneralBackground($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Theme whereGeneralBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Theme whereGeneralHeading($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Theme whereGeneralIcons($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Theme whereHeaderBackground($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Theme whereHeaderText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Theme whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Theme whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Theme whereMenuName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Theme whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Theme whereNavbarBackground($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Theme whereNavbarText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Theme whereTableBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Theme whereTableHeading($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Theme whereTableTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Theme whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Theme extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $table = 'themes';

    protected $fillable = [
        'name',
        'button_primary',
        'button_secondary',
        'is_default',
        'menu_name',
        'general_heading',
        'general_body',
        'general_icons',
        'general_background',
        'table_heading',
        'table_body',
        'table_title',
        'header_background',
        'header_text',
        'navbar_text',
        'navbar_background',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'menu_name' => 'json',
    ];

    public function entity(): HasOne
    {
        return $this->hasOne(Entity::class);
    }

    public function getLogoUrl(): ?string
    {
        return $this->getFirstMediaUrl('logos');
    }

    public function getBannerUrl(): ?string
    {
        return $this->getFirstMediaUrl('banners');
    }
}
