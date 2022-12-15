<?php

namespace App\Models;

use App\Traits\UUID;
use Eloquent;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\PropertyAddress
 *
 * @property string $property_id
 * @property string $line_1
 * @property string|null $line_2
 * @property string $city
 * @property string|null $state
 * @property string $postal_code
 * @property int $updated_at
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Property $property
 * @method static EloquentBuilder|PropertyAddress newModelQuery()
 * @method static EloquentBuilder|PropertyAddress newQuery()
 * @method static EloquentBuilder|PropertyAddress query()
 * @method static EloquentBuilder|PropertyAddress whereCity($value)
 * @method static EloquentBuilder|PropertyAddress whereLine1($value)
 * @method static EloquentBuilder|PropertyAddress whereLine2($value)
 * @method static EloquentBuilder|PropertyAddress wherePostalCode($value)
 * @method static EloquentBuilder|PropertyAddress wherePropertyId($value)
 * @method static EloquentBuilder|PropertyAddress whereState($value)
 * @method static EloquentBuilder|PropertyAddress whereUpdatedAt($value)
 * @mixin Eloquent
 */
class PropertyAddress extends Model
{
    use HasFactory, Notifiable, UUID;

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
    public $timestamps = [ "updated_at" ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'addresses';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'property_id';

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
        'property_id',
        'line_1',
        'line_2',
        'city',
        'state',
        'postal_code'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['property_id'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = ['updated_at' => 'timestamp'];

    /**
     * Get the property that owns the rooms.
     *
     * @return BelongsTo
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id', 'id');
    }
}
