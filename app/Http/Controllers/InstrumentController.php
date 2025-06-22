<?php

namespace App\Http\Controllers;

use App\Http\Requests\Instrument\StoreRequest;
use App\Http\Requests\Instrument\UpdateRequest;
use App\Http\Resources\Instrument\InstrumentCollection;
use App\Http\Resources\Instrument\InstrumentResource;
use App\Models\Instrument;
use App\Services\InstrumentService;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\QueryBuilder;

class InstrumentController extends Controller
{
    public function __construct(private InstrumentService $instrumentService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // implementation of Spatie query builder
        $instruments = QueryBuilder::for(Instrument::class)
            ->allowedFilters(['name', 'category.name'])
            ->allowedSorts(['name', 'created_at'])
            ->paginate(12);

        return new InstrumentCollection($instruments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $validatedData = $request->validated();

        try {
            $instrument = $this->instrumentService->create($validatedData);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                'error' => [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                ],
            ], 500);
        }

        return new InstrumentResource($instrument);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Instrument $instrument)
    {
        $validatedData = $request->validated();

        try {
            $instrument = $this->instrumentService->update($instrument, $validatedData);
        } catch (\Exception $e) {
            // Handle exception, log error, etc.
            return response()->json(['error' => 'Failed to update instrument'], 500);
        }

        return new InstrumentResource($instrument);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Instrument $instrument)
    {
        try {
            $this->instrumentService->delete($instrument);
        } catch (\Exception $e) {
            // Handle exception, log error, etc.
            return response()->json(['error' => 'Failed to delete instrument'], 500);
        }

        return response()->json(['message' => 'Instrument deleted successfully'], 204);
    }
}
