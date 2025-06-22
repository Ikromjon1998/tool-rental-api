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
 * @property string|null $description
 * @property string|null $icon
 * @property bool $is_active
 * @property int $sort_order
 *
 * // $table->text('description')->nullable()->after('name');
 * $table->string('icon')->nullable()->after('description');
 * $table->boolean('is_active')->default(true)->after('icon');
 * $table->integer('sort_order')->default(0)->after('is_active');
 *
 * // create method from Model
 *
 * @method static Category create(array $attributes = [])
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
        'description',
        'icon',
        'is_active',
        'sort_order',
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
