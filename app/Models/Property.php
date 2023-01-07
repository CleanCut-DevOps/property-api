<?php

namespace App\Models;

use App\Traits\UUID;
use Eloquent;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Http;

/**
 * App\Models\Property
 *
 * @property string $id
 * @property string|null $user_id
 * @property string|null $type_id
 * @property string $icon
 * @property string $label
 * @property string|null $description
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $deleted_at
 * @property-read Model|null $address
 * @property-read Collection|PropertyImage[] $images
 * @property-read Collection|PropertyRooms[] $rooms
 * @property-read Collection|null $type
 * @property-read int|null $images_count
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read int|null $rooms_count
 * @method static EloquentBuilder|Property newModelQuery()
 * @method static EloquentBuilder|Property newQuery()
 * @method static QueryBuilder|Property onlyTrashed()
 * @method static EloquentBuilder|Property query()
 * @method static EloquentBuilder|Property whereCreatedAt($value)
 * @method static EloquentBuilder|Property whereDeletedAt($value)
 * @method static EloquentBuilder|Property whereDescription($value)
 * @method static EloquentBuilder|Property whereIcon($value)
 * @method static EloquentBuilder|Property whereId($value)
 * @method static EloquentBuilder|Property whereLabel($value)
 * @method static EloquentBuilder|Property whereTypeId($value)
 * @method static EloquentBuilder|Property whereUpdatedAt($value)
 * @method static EloquentBuilder|Property whereUserId($value)
 * @method static QueryBuilder|Property withTrashed()
 * @method static QueryBuilder|Property withoutTrashed()
 * @mixin Eloquent
 */
class Property extends Model
{
    use HasFactory, SoftDeletes, Notifiable, UUID;

    public $appends = ["type", "address", "rooms", "images"];

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
    protected $table = "property";

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = "id";

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
        "user_id",
        "type_id",
        "icon",
        "label",
        "description",
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ["deleted_at", "type_id"];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        "deleted_at" => "timestamp",
        "created_at" => "timestamp",
        "updated_at" => "timestamp",
    ];

    /**
     * Get the property"s type.
     *
     * @return Collection|null
     */
    public function getTypeAttribute(): array|null
    {
        $typeID = $this->type_id;

        if ($typeID) {
            $serviceAPI = config("env.SERVICE_API");

            $response = Http::accept("application/json")->get("$serviceAPI/property/$typeID");

            if ($response->successful()) {
                return $response["propertyType"];
            } else {
                return null;
            }
        } else return null;
    }

    /**
     * Get the property"s rooms.
     *
     * @return array|null
     */
    public function getRoomsAttribute(): array|null
    {
        $rooms = [];
        $rawRoomsData = $this->rooms()->get();

        if (count($rawRoomsData) > 0) {
            $serviceAPI = config("env.SERVICE_API");

            foreach ($rawRoomsData as $rawRoomData) {
                $roomID = $rawRoomData->room_id;
                $response = Http::accept("application/json")->get("$serviceAPI/room/$roomID");
                if ($response->successful()) {
                    $rooms[] = $response["roomType"];
                }
            }
        }

        return $rooms;
    }

    /**
     * Get the property that owns the image.
     *
     * @return HasMany
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(PropertyRooms::class, "property_id", "id");
    }

    /**
     * Get the property"s address.
     *
     * @return Model|null
     */
    public function getAddressAttribute(): Model|null
    {
        return $this->address()->first();
    }

    /**
     * Get the property that owns the image.
     *
     * @return HasOne
     */
    public function address(): HasOne
    {
        return $this->hasOne(PropertyAddress::class, "property_id", "id");
    }

    /**
     * Get the property"s images.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getImagesAttribute(): \Illuminate\Support\Collection
    {
        $raw = $this->images()->get();

        return $raw->map(fn($image) => $image->url);
    }

    /**
     * Get the property that owns the image.
     *
     * @return HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany(PropertyImage::class, "property_id", "id");
    }
}
