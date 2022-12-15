<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

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
