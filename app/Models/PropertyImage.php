<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\PropertyImage
 *
 * @property string $public_id
 * @property string $property_id
 * @property string $url
 * @property-read Property $property
 * @method static Builder|PropertyImage newModelQuery()
 * @method static Builder|PropertyImage newQuery()
 * @method static Builder|PropertyImage query()
 * @method static Builder|PropertyImage wherePublicId($value)
 * @method static Builder|PropertyImage wherePropertyId($value)
 * @method static Builder|PropertyImage whereUrl($value)
 * @mixin Eloquent
 */
class PropertyImage extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "images";

    /**
     * The data type of the ID.
     *
     * @var string
     */
    protected $keyType = "string";

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ["property_id", "public_id"];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ["public_id", "property_id", "url"];


    /**
     * Get the property that owns the image.
     *
     * @return BelongsTo
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, "property_id", "id");
    }
}
