<?php

namespace App\Services;

use App\Models\Instrument;

class InstrumentService
{
    public function create(array $data): Instrument
    {
        return Instrument::create($data);
    }

    public function update(Instrument $instrument, array $data): Instrument
    {
        $instrument->update($data);

        return $instrument;
    }

    public function delete(Instrument $instrument): void
    {
        $instrument->delete();
    }
}
