<?php

namespace App\Models;

use App\Traits\UUID;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
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
 * @property string|null $line_1
 * @property string|null $line_2
 * @property string|null $city
 * @property string|null $state
 * @property string|null $postal_code
 * @property int $updated_at
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Property $property
 * @method static Builder|PropertyAddress newModelQuery()
 * @method static Builder|PropertyAddress newQuery()
 * @method static Builder|PropertyAddress query()
 * @method static Builder|PropertyAddress whereCity($value)
 * @method static Builder|PropertyAddress whereLine1($value)
 * @method static Builder|PropertyAddress whereLine2($value)
 * @method static Builder|PropertyAddress wherePostalCode($value)
 * @method static Builder|PropertyAddress wherePropertyId($value)
 * @method static Builder|PropertyAddress whereState($value)
 * @method static Builder|PropertyAddress whereUpdatedAt($value)
 * @mixin Eloquent
 */
class PropertyAddress extends Model
{
    use HasFactory, Notifiable, UUID;

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
    protected $table = "addresses";

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = "property_id";

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
        "line_1",
        "line_2",
        "city",
        "state",
        "zip"
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
