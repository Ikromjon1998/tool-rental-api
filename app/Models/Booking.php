<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        return $this->belongsTo(User::class);
    }

    /**
     * Get the instrument that belongs to the booking.
     *
     * @return BelongsTo<Instrument, Booking>
     */
    public function instrument(): BelongsTo
    {
        return $this->belongsTo(Instrument::class);
    }
}
