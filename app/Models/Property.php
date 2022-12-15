<?php

namespace App\Models;

use App\Traits\UUID;
use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\Property
 *
 * @property string $id
 * @property string $user_id
 * @property string $type_id
 * @property string $name
 * @property string $description
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $deleted_at
 * @property-read Collection $address
 * @property-read Collection|PropertyImage[] $images
 * @property-read PropertyRooms|null $rooms
 * @property-read PropertyType $type
 * @property-read int|null $images_count
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static EloquentBuilder|Property newModelQuery()
 * @method static EloquentBuilder|Property newQuery()
 * @method static QueryBuilder|Property onlyTrashed()
 * @method static EloquentBuilder|Property query()
 * @method static EloquentBuilder|Property whereCreatedAt($value)
 * @method static EloquentBuilder|Property whereDeletedAt($value)
 * @method static EloquentBuilder|Property whereDescription($value)
 * @method static EloquentBuilder|Property whereId($value)
 * @method static EloquentBuilder|Property whereName($value)
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

    public $appends = ['images', 'rooms', 'address', 'type'];

    /**
     * Indicates if the model's ID is auto-incrementing.
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
    protected $table = 'properties';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * The data type of the ID.
     *
     * @var string
     */
    protected $keyType = 'string';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'type_id',
        'name',
        'description',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['deleted_at'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'deleted_at' => 'timestamp',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    /**
     * Get the type that owns the property.
     *
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(PropertyType::class, 'type_id', 'id');
    }

    /**
     * Get the property that owns the image.
     *
     * @return HasOne
     */
    public function rooms(): HasOne
    {
        return $this->hasOne(PropertyRooms::class, 'property_id', 'id');
    }

    /**
     * Get the property that owns the image.
     *
     * @return HasOne
     */
    public function address(): HasOne
    {
        return $this->hasOne(PropertyAddress::class, 'property_id', 'id');
    }

    /**
     * Get the property that owns the image.
     *
     * @return HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany(PropertyImage::class, 'property_id', 'id');
    }

    /**
     * Get the property's type.
     *
     * @return Collection
     */
    public function getTypeAttribute(): Collection
    {
        return $this->type()->get();
    }

    /**
     * Get the property's rooms.
     *
     * @return Collection
     */
    public function getRoomsAttribute(): Collection
    {
        return $this->rooms()->get();
    }

    /**
     * Get the property's address.
     *
     * @return Collection
     */
    public function getAddressAttribute(): Collection
    {
        return $this->address()->get();
    }

    /**
     * Get the property's images.
     *
     * @return Collection
     */
    public function getImagesAttribute(): Collection
    {
        return $this->images()->get();
    }
}
