<?php

namespace App\Http\Controllers;

use App\Http\Requests\Instrument\StoreRequest;
use App\Http\Requests\Instrument\UpdateRequest;
use App\Http\Resources\Instrument\InstrumentCollection;
use App\Http\Resources\Instrument\InstrumentResource;
use App\Models\Instrument;
use App\Services\InstrumentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class InstrumentController extends Controller
{
    /**
     * InstrumentController constructor.
     */
    public function __construct(private readonly InstrumentService $instrumentService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): InstrumentCollection
    {
        // implementation of Spatie query builder
        $instruments = QueryBuilder::for(Instrument::class)
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('category_id'),
                AllowedFilter::scope('search'), // Custom scope for searching by name or description
            ])
            ->allowedSorts(['name', 'created_at'])
            ->allowedIncludes(['category', 'bookings'])
            ->paginate(12);

        return new InstrumentCollection($instruments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request): JsonResponse|InstrumentResource
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
    public function update(UpdateRequest $request, Instrument $instrument): JsonResponse|InstrumentResource
    {
        $validatedData = $request->validated();

        try {
            $instrument = $this->instrumentService->update($instrument, $validatedData);
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
     * Remove the specified resource from storage.
     */
    public function destroy(Instrument $instrument): JsonResponse
    {
        try {
            $this->instrumentService->delete($instrument);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                'error' => [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                ],
            ], 500);
        }

        return response()->json(['message' => 'Instrument deleted successfully'], 204);
    }
}
