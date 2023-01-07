<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\PropertyRooms
 *
 * @property string $property_id
 * @property string $room_id
 * @property int $quantity
 * @property int $updated_at
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
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
    use HasFactory, Notifiable;

    const CREATED_AT = null;

    /**
     * Indicates if the model"s ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "rooms";

    /**
     * The data type of the ID.
     *
     * @var string
     */
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
    protected $hidden = ["property_id"];

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
        return $this->belongsTo(Property::class, "property_id", "id");
    }
}
