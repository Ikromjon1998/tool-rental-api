<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        return $this->hasMany(Instrument::class);
    }
}
