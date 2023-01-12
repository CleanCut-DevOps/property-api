<?php

namespace App\Models;

use App\Traits\UUID;
use Eloquent;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;

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
 * @property-read PropertyType|null $type
 * @property-read int|null $images_count
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
    use SoftDeletes, UUID;

    public $appends = ["type", "address", "rooms", "images"];

    public $timestamps = true;
    public $incrementing = false;
    protected $table = "property";
    protected $primaryKey = "id";
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
     * Appends the property's type.
     *
     * @return Model | null
     */
    public function getTypeAttribute(): Model|null
    {
        $typeID = $this->type_id;

        if ($typeID) {
            return $this->type()->first();
        } else return null;
    }

    /**
     * Get the property's type.
     *
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(PropertyType::class);
    }

    /**
     * Appends the property's rooms.
     *
     * @return Collection
     */
    public function getRoomsAttribute(): Collection
    {
        return $this->rooms()->get();
    }

    /**
     * Get the property's rooms.
     *
     * @return HasMany
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(PropertyRooms::class);
    }

    /**
     * Appends the property's address.
     *
     * @return Model|null
     */
    public function getAddressAttribute(): Model|null
    {
        return $this->address()->first();
    }

    /**
     * Get the property's address.
     *
     * @return HasOne
     */
    public function address(): HasOne
    {
        return $this->hasOne(PropertyAddress::class);
    }

    /**
     * Appends the property's image urls.
     *
     * @return Collection
     */
    public function getImagesAttribute(): \Illuminate\Support\Collection
    {
        $raw = $this->images()->get();

        return $raw->map(fn($image) => $image->url);
    }

    /**
     * Get the property's images.
     *
     * @return HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany(PropertyImage::class);
    }


}
