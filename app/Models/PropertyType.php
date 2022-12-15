<?php

namespace App\Models;

use App\Traits\UUID;
use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\PropertyType
 *
 * @property string $id
 * @property string $label
 * @property float $bedroom_price
 * @property float $bathroom_price
 * @property float $toilet_price
 * @property float $kitchen_price
 * @property float $living_room_price
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $deleted_at
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection|Property[] $properties
 * @property-read int|null $properties_count
 * @method static EloquentBuilder|PropertyType newModelQuery()
 * @method static EloquentBuilder|PropertyType newQuery()
 * @method static QueryBuilder|PropertyType onlyTrashed()
 * @method static EloquentBuilder|PropertyType query()
 * @method static EloquentBuilder|PropertyType whereBathroomPrice($value)
 * @method static EloquentBuilder|PropertyType whereBedroomPrice($value)
 * @method static EloquentBuilder|PropertyType whereCreatedAt($value)
 * @method static EloquentBuilder|PropertyType whereDeletedAt($value)
 * @method static EloquentBuilder|PropertyType whereId($value)
 * @method static EloquentBuilder|PropertyType whereKitchenPrice($value)
 * @method static EloquentBuilder|PropertyType whereLabel($value)
 * @method static EloquentBuilder|PropertyType whereLivingRoomPrice($value)
 * @method static EloquentBuilder|PropertyType whereToiletPrice($value)
 * @method static EloquentBuilder|PropertyType whereUpdatedAt($value)
 * @method static QueryBuilder|PropertyType withTrashed()
 * @method static QueryBuilder|PropertyType withoutTrashed()
 * @mixin Eloquent
 */
class PropertyType extends Model
{
    use HasFactory, SoftDeletes, Notifiable, UUID;

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
    protected $table = 'types';

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
        'label',
        'bedroom_price',
        'bathroom_price',
        'toilet_price',
        'kitchen_price',
        'living_room_price',
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
     * Get the properties under this type.
     *
     * @return HasMany
     */
    public function properties(): HasMany
    {
        return $this->hasMany(Property::class, 'type_id', 'id');
    }
}
