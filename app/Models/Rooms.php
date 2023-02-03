<?php

namespace App\Models;

use App\Traits\UUID;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Rooms
 *
 * @property string $property_id
 * @property string $type_id
 * @property int $quantity
 * @property Carbon $updated_at
 * @property-read RoomType $type
 * @property-read Property $property
 * @method static Builder|Rooms newModelQuery()
 * @method static Builder|Rooms newQuery()
 * @method static Builder|Rooms query()
 * @method static Builder|Rooms wherePropertyId($value)
 * @method static Builder|Rooms whereQuantity($value)
 * @method static Builder|Rooms whereTypeId($value)
 * @method static Builder|Rooms whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Rooms extends Model
{
    use UUID;

    const CREATED_AT = null;

    public $appends = ["type"];

    public $timestamps = true;
    protected $table = "rooms";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "property_id",
        "type_id",
        "quantity"
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ["property_id", "type_id"];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = ["updated_at" => "datetime"];

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
        return $this->type()->first();
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
