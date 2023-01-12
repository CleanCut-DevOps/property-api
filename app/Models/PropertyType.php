<?php

namespace App\Models;

use App\Traits\UUID;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\PropertyType
 *
 * @property string $id
 * @property string $label
 * @property string $description
 * @property string|null $detailed_description
 * @property bool $available
 * @property-read Collection|Property[] $properties
 * @property-read int|null $properties_count
 * @property-read Collection|RoomType[] $rooms
 * @property-read int|null $rooms_count
 * @method static Builder|PropertyType newModelQuery()
 * @method static Builder|PropertyType newQuery()
 * @method static Builder|PropertyType query()
 * @method static Builder|PropertyType whereAvailable($value)
 * @method static Builder|PropertyType whereDescription($value)
 * @method static Builder|PropertyType whereDetailedDescription($value)
 * @method static Builder|PropertyType whereId($value)
 * @method static Builder|PropertyType whereLabel($value)
 * @mixin Eloquent
 */
class PropertyType extends Model
{
    use UUID;

    public $incrementing = false;
    public $timestamps = false;
    protected $table = 'type';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'label',
        'description',
        'detailed_description',
        'available',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = ["available" => "boolean"];

    /**
     * Get the properties for the type.
     *
     * @return HasMany
     */
    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }

    /**
     * Get the type's room types.
     *
     * @return HasMany
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(RoomType::class);
    }
}
