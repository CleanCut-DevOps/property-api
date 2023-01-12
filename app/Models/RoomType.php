<?php

namespace App\Models;

use App\Traits\UUID;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\RoomType
 *
 * @property string $id
 * @property string $type_id
 * @property string $label
 * @property float $price
 * @property bool $available
 * @property-read Collection|PropertyRooms[] $rooms
 * @property-read int|null $rooms_count
 * @property-read PropertyType $type
 * @method static Builder|RoomType newModelQuery()
 * @method static Builder|RoomType newQuery()
 * @method static Builder|RoomType query()
 * @method static Builder|RoomType whereAvailable($value)
 * @method static Builder|RoomType whereId($value)
 * @method static Builder|RoomType whereLabel($value)
 * @method static Builder|RoomType wherePrice($value)
 * @method static Builder|RoomType whereTypeId($value)
 * @mixin Eloquent
 */
class RoomType extends Model
{
    use UUID;

    public $incrementing = false;
    public $timestamps = false;
    protected $table = 'room_type';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'type_id',
        'label',
        'price',
        'available',
    ];

    protected $casts = [
        'available' => 'boolean',
    ];

    /**
     * Get the type that owns the room.
     *
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(PropertyType::class);
    }

    /**
     * Get the type's rooms.
     *
     * @return HasMany
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(PropertyRooms::class);
    }
}
