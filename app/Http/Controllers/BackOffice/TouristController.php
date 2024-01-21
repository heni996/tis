<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tourist\CreateTouristRequest;
use App\Http\Requests\Tourist\UpdateTouristRequest;
use App\Http\Resources\BackOffice\TouristResource;
use App\Http\Services\BackOffice\TouristService;
use App\Models\Tourist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TouristController extends Controller
{
    private TouristService $TouristService;
    private Tourist $TouristModel;
    const Tourist_NOT_FOUND_MESSAGE = "Tourist non trouvé.";

    public function __construct(TouristService $TouristService)
    {
        $this->TouristService = $TouristService;
        $this->TouristModel = new Tourist();
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $response = $this->TouristService
            ->getTourists($this->TouristModel, $request);
        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return JsonResponse
     */
    public function store(CreateTouristRequest $request): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $TouristData = $request->validated();
        $filePath = uploadFile($request, 'Tourist_files', 'file');
        if ($filePath) {
            $TouristData['file'] = $filePath;
        }
        $Tourist = $this->TouristService
            ->createTourist($TouristData, $this->TouristModel);
        return response()->json([
            'message' => "Tourist a été créé avec succès.",
            'data' => new TouristResource($Tourist)
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $Tourist = $this->TouristService
            ->getTouristById($id, $this->TouristModel);

        if (!$Tourist) {
            return response()->json(['message' => self::Tourist_NOT_FOUND_MESSAGE], 404);
        }

        return response()->json(new TouristResource($Tourist));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateTouristRequest $request, int $id): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $TouristData = $request->validated();
        $filePath = uploadFile($request, 'Tourist_files', 'file');
        if ($filePath) {
            $TouristData['file'] = $filePath;
        }
        $Tourist = $this->TouristService
            ->getTouristById($id, $this->TouristModel);

        if (!$Tourist) {

            return response()->json(['message' => self::Tourist_NOT_FOUND_MESSAGE], 404);
        }

        $this->TouristService->editTourist($Tourist, $TouristData);
        return response()->json([
            'message' => "Tourist a été modifié avec succée.",
            'data' =>  new TouristResource($Tourist)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $Tourist = $this->TouristService
            ->getTouristById($id, $this->TouristModel);

        if (!$Tourist) {

            return response()->json(['message' => self::Tourist_NOT_FOUND_MESSAGE], 404);
        }

        $this->TouristService->deleteTourist($Tourist);

        return response()->json(['message' => "Tourist a été supprimé avec succée."], 202);
    }
}
