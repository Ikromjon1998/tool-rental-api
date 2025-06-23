<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $user_id
 * @property int $instrument_id
 * @property string $start_date
 * @property string $end_date
 * @property string $status
 * @property-read User $user
 * @property-read Instrument $instrument
 */
class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['user_id', 'instrument_id', 'start_date', 'end_date', 'status'];

    /**
     * Get the user that owns the booking.
     *
     * @return BelongsTo<User, Booking>
     */
    public function user(): BelongsTo
    {
        /** @var BelongsTo<User, self> */
        return $this->belongsTo(User::class);
    }

    /**
     * Get the instrument that belongs to the booking.
     *
     * @return BelongsTo<Instrument, Booking>
     */
    public function instrument(): BelongsTo
    {
        /** @var BelongsTo<Instrument, self> */
        return $this->belongsTo(Instrument::class);
    }
}
