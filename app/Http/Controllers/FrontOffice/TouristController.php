<?php

namespace App\Http\Controllers\FrontOffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tourist\CreateTouristRequest;
use App\Http\Requests\Tourist\UpdateTouristRequest;
use App\Http\Resources\FrontOffice\TouristResource;
use App\Http\Services\FrontOffice\TouristService;
use App\Models\Tourist;
use App\Models\Trainer;
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
        // $filePath = uploadFile($request, 'Tourist_files', 'file');
        // if ($filePath) {
        //     $TouristData['file'] = $filePath;
        // }
        $TouristData['code'] = $this->generateUniqueCode();
        $TouristData['is_valid'] = false;
        $Tourist = $this->TouristService
            ->createTourist($TouristData, $this->TouristModel);
        $Tourist->hotels()->attach($TouristData['hotel_ids']);
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
        // $filePath = uploadFile($request, 'Tourist_files', 'file');
        // if ($filePath) {
        //     $TouristData['file'] = $filePath;
        // }
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

    public function validateCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $code = strtoupper($request->input('code'));

        $tourist = Tourist::where('code', $code)->first();

        if (!$tourist) {
            return response()->json(['error' => 'Invalid code'], 404);
        }

        if ($tourist->is_valid) {
            throw new \Exception('Code is already valid.');
        }

        $tourist->update(['is_valid' => true]);

        return response()->json(['message' => 'Code validated successfully']);
    }

    public function generateUniqueCode()
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersNumber = strlen($characters);
        $codeLength = 6;

        $code = '';

        while (strlen($code) < $codeLength) {
            $position = rand(0, $charactersNumber - 1);
            $character = $characters[$position];
            $code = $code . $character;
        }

        if (Trainer::where('code', $code)->exists()) {
            return $this->generateUniqueCode();
        }

        return $code;
    }
}

