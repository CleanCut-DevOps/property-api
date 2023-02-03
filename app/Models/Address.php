<?php

namespace App\Models;

use App\Traits\UUID;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Address
 *
 * @property string $property_id
 * @property string|null $line_1
 * @property string|null $line_2
 * @property string|null $city
 * @property string|null $state
 * @property string|null $zip
 * @property Carbon $updated_at
 * @property-read Property $property
 * @method static Builder|Address newModelQuery()
 * @method static Builder|Address newQuery()
 * @method static Builder|Address query()
 * @method static Builder|Address whereCity($value)
 * @method static Builder|Address whereLine1($value)
 * @method static Builder|Address whereLine2($value)
 * @method static Builder|Address wherePropertyId($value)
 * @method static Builder|Address whereState($value)
 * @method static Builder|Address whereUpdatedAt($value)
 * @method static Builder|Address whereZip($value)
 * @mixin Eloquent
 */
class Address extends Model
{
    use UUID;

    const CREATED_AT = null;

    public $timestamps = true;
    public $incrementing = false;
    protected $primaryKey = "property_id";
    protected $keyType = "string";
    protected $table = "addresses";

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
    protected $casts = ["updated_at" => "datetime"];

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
