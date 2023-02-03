<?php

namespace App\Models;

use App\Traits\UUID;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Images
 *
 * @property string $property_id
 * @property string $public_id
 * @property string $url
 * @property-read Property $property
 * @method static Builder|Images newModelQuery()
 * @method static Builder|Images newQuery()
 * @method static Builder|Images query()
 * @method static Builder|Images wherePropertyId($value)
 * @method static Builder|Images wherePublicId($value)
 * @method static Builder|Images whereUrl($value)
 * @mixin Eloquent
 */
class Images extends Model
{
    public $timestamps = false;
    protected $table = "images";


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ["public_id", "property_id", "url"];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ["property_id", "public_id"];



    /**
     * Get the property that owns the image.
     *
     * @return BelongsTo
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
}
