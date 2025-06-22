<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property float $price
 * @property \Illuminate\Support\Carbon|null $bought_at
 * @property \Illuminate\Support\Carbon|null $first_used_at
 * @property int|null $category_id
 * @property-read Category|null $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Booking> $bookings
 * @property-read int|null $bookings_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $savedByUser
 * @property-read int|null $saved_by_user_count
 */
class Instrument extends Model implements HasMedia
{
    use InteractsWithMedia, SoftDeletes, Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'bought_at',
        'first_used_at',
        'category_id',
    ];

    /**
     * @var list<string>
     */
    protected $searchanble = [
        'name',
        'description',
        'category.name',
        'category.description',
    ];

    /**
     * The attributes that are mass assignable.
     */
    public function category(): BelongsTo
    {
        /** @var BelongsTo<Category, self> */
        return $this->belongsTo(Category::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @return HasMany<Booking, Instrument>
     */
    public function bookings(): HasMany
    {
        /** @var HasMany<Booking, self> */
        return $this->hasMany(Booking::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @return BelongsToMany<User, Instrument>
     */
    public function savedByUser(): BelongsToMany
    {
        /** @var BelongsToMany<User, self> */
        return $this->belongsToMany(User::class, 'saved_items');
    }
}
