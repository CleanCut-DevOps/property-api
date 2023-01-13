<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\PropertyRooms
 *
 * @property string $property_id
 * @property string $room_id
 * @property int $quantity
 * @property int $updated_at
 * @property-read RoomType $type
 * @property-read Property $property
 * @method static Builder|PropertyRooms newModelQuery()
 * @method static Builder|PropertyRooms newQuery()
 * @method static Builder|PropertyRooms query()
 * @method static Builder|PropertyRooms wherePropertyId($value)
 * @method static Builder|PropertyRooms whereQuantity($value)
 * @method static Builder|PropertyRooms whereRoomId($value)
 * @method static Builder|PropertyRooms whereUpdatedAt($value)
 * @mixin Eloquent
 */
class PropertyRooms extends Model
{
    const CREATED_AT = null;

    public $appends = ["type"];

    public $incrementing = false;
    public $timestamps = true;
    protected $table = "rooms";
    protected $keyType = "string";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "property_id",
        "room_id",
        "quantity"
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ["property_id", "room_id"];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = ["updated_at" => "timestamp"];

    /**
     * Get the property that owns the rooms.
     *
     * @return BelongsTo
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Appends the room's type.
     *
     * @return Model
     */
    public function getTypeAttribute(): Model
    {
        return RoomType::whereId($this->room_id)->first();
    }

    /**
     * Get the room's type.
     *
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }
}
