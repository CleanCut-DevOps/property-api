<?php

namespace App\Models;

use App\Traits\UUID;
use Database\Factories\RoomTypeFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\RoomType
 *
 * @property string $id
 * @property string $label
 * @property bool $available
 * @property float $price
 * @property string $property_type_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read PropertyType $propertyType
 * @property-read Collection|Rooms[] $rooms
 * @property-read int|null $rooms_count
 * @method static RoomTypeFactory factory(...$parameters)
 * @method static Builder|RoomType newModelQuery()
 * @method static Builder|RoomType newQuery()
 * @method static Builder|RoomType query()
 * @method static Builder|RoomType whereAvailable($value)
 * @method static Builder|RoomType whereCreatedAt($value)
 * @method static Builder|RoomType whereId($value)
 * @method static Builder|RoomType whereLabel($value)
 * @method static Builder|RoomType wherePrice($value)
 * @method static Builder|RoomType wherePropertyTypeId($value)
 * @method static Builder|RoomType whereUpdatedAt($value)
 * @mixin Eloquent
 */
class RoomType extends Model
{
    use HasFactory, UUID;

    public $timestamps = true;
    public $incrementing = false;
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $table = 'room_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'label',
        'price',
        'available',
        'property_type_id'
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
     * Get property type that owns this type.
     *
     * @return BelongsTo
     */
    public function propertyType(): BelongsTo
    {
        return $this->belongsTo(PropertyType::class);
    }

    /**
     * Get rooms of this type.
     *
     * @return HasMany
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(Rooms::class);
    }
}
