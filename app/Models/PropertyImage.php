<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * App\Models\PropertyImage
 *
 * @property int $id
 * @property string $property_id
 * @property string $path
 * @property-read Property $property
 * @method static EloquentBuilder|PropertyImage newModelQuery()
 * @method static EloquentBuilder|PropertyImage newQuery()
 * @method static QueryBuilder|PropertyImage onlyTrashed()
 * @method static EloquentBuilder|PropertyImage query()
 * @method static EloquentBuilder|PropertyImage whereId($value)
 * @method static EloquentBuilder|PropertyImage wherePath($value)
 * @method static EloquentBuilder|PropertyImage wherePropertyId($value)
 * @method static QueryBuilder|PropertyImage withTrashed()
 * @method static QueryBuilder|PropertyImage withoutTrashed()
 * @mixin Eloquent
 * @property string|null $caption
 * @property int|null $deleted_at
 * @property int|null $created_at
 * @property int|null $updated_at
 * @method static EloquentBuilder|PropertyImage whereCaption($value)
 * @method static EloquentBuilder|PropertyImage whereCreatedAt($value)
 * @method static EloquentBuilder|PropertyImage whereDeletedAt($value)
 * @method static EloquentBuilder|PropertyImage whereUpdatedAt($value)
 */
class PropertyImage extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;
    protected $table = 'property_images';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'property_id',
        'path',
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
     * Get the property that owns the image.
     *
     * @return BelongsTo
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id', 'id');
    }
}
