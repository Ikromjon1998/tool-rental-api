<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        return $this->belongsTo(User::class);
    }

    /**
     * Get the instrument that was saved.
     *
     * @return BelongsTo<Instrument, SavedItem>
     */
    public function instrument(): BelongsTo
    {
        return $this->belongsTo(Instrument::class);
    }
}
