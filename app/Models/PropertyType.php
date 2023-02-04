<?php

namespace App\Models;

use App\Traits\UUID;
use Database\Factories\PropertyTypeFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\PropertyType
 *
 * @property string $id
 * @property string $label
 * @property string $description
 * @property bool $available
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Property[] $properties
 * @property-read int|null $properties_count
 * @property-read Collection|RoomType[] $rooms
 * @property-read int|null $rooms_count
 * @method static PropertyTypeFactory factory(...$parameters)
 * @method static Builder|PropertyType newModelQuery()
 * @method static Builder|PropertyType newQuery()
 * @method static Builder|PropertyType query()
 * @method static Builder|PropertyType whereAvailable($value)
 * @method static Builder|PropertyType whereCreatedAt($value)
 * @method static Builder|PropertyType whereDescription($value)
 * @method static Builder|PropertyType whereId($value)
 * @method static Builder|PropertyType whereLabel($value)
 * @method static Builder|PropertyType whereUpdatedAt($value)
 * @mixin Eloquent
 */
class PropertyType extends Model
{
    use HasFactory, UUID;

    public $timestamps = true;
    public $incrementing = false;
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $table = 'property_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'label',
        'available',
        'description'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'available' => 'boolean'
    ];

    /**
     * Get properties of this type.
     *
     * @return HasMany
     */
    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }

    /**
     * Get room types of this type.
     *
     * @return HasMany
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(RoomType::class);
    }
}
