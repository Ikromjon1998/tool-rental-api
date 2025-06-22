<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $user_id
 * @property int $instrument_id
 * @property-read User $user
 * @property-read Instrument $instrument
 */
class SavedItem extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'instrument_id'];

    /**
     * Get the user that saved the item.
     *
     * @return BelongsTo<User, SavedItem>
     */
    public function user(): BelongsTo
    {
        /** @var BelongsTo<User, self> */
        return $this->belongsTo(User::class);
    }

    /**
     * Get the instrument that was saved.
     *
     * @return BelongsTo<Instrument, SavedItem>
     */
    public function instrument(): BelongsTo
    {
        /** @var BelongsTo<Instrument, self> */
        return $this->belongsTo(Instrument::class);
    }
}
