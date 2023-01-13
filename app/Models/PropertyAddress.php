<?php

namespace App\Models;

use App\Traits\UUID;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\PropertyAddress
 *
 * @property string $property_id
 * @property string|null $line_1
 * @property string|null $line_2
 * @property string|null $city
 * @property string|null $state
 * @property string|null $zip
 * @property int $updated_at
 * @property-read Property $property
 * @method static Builder|PropertyAddress newModelQuery()
 * @method static Builder|PropertyAddress newQuery()
 * @method static Builder|PropertyAddress query()
 * @method static Builder|PropertyAddress whereCity($value)
 * @method static Builder|PropertyAddress whereLine1($value)
 * @method static Builder|PropertyAddress whereLine2($value)
 * @method static Builder|PropertyAddress wherePropertyId($value)
 * @method static Builder|PropertyAddress whereState($value)
 * @method static Builder|PropertyAddress whereUpdatedAt($value)
 * @method static Builder|PropertyAddress whereZip($value)
 * @mixin Eloquent
 */
class PropertyAddress extends Model
{
    use UUID;

    const CREATED_AT = null;

    public $incrementing = false;
    public $timestamps = true;
    protected $table = "addresses";
    protected $primaryKey = "property_id";
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
     * Get the property that owns the address.
     *
     * @return BelongsTo
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
}
