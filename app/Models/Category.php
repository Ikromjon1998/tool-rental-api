<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Instrument> $instruments
 * @property-read int|null $instruments_count
 */
class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get the instruments that belong to the category.
     *
     * @return HasMany<Instrument, Category>
     */
    public function instruments(): HasMany
    {
        /** @var HasMany<Instrument, self> */
        return $this->hasMany(Instrument::class);
    }
}
