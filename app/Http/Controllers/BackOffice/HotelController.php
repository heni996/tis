<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hotel\CreateHotelRequest;
use App\Http\Requests\Hotel\UpdateHotelRequest;
use App\Http\Resources\BackOffice\HotelResource;
use App\Http\Services\BackOffice\HotelService;
use App\Models\Hotel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    private HotelService $HotelService;
    private Hotel $HotelModel;
    const Hotel_NOT_FOUND_MESSAGE = "Hotel non trouvé.";

    public function __construct(HotelService $HotelService)
    {
        $this->HotelService = $HotelService;
        $this->HotelModel = new Hotel();
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
        $response = $this->HotelService
            ->getHotels($this->HotelModel, $request);
        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return JsonResponse
     */
    public function store(CreateHotelRequest $request): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $HotelData = $request->validated();
        $filePath = uploadFile($request, 'Hotel_files', 'file');
        if ($filePath) {
            $HotelData['file'] = $filePath;
        }
        $Hotel = $this->HotelService
            ->createHotel($HotelData, $this->HotelModel);
        return response()->json([
            'message' => "Hotel a été créé avec succès.",
            'data' => new HotelResource($Hotel)
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
        $Hotel = $this->HotelService
            ->getHotelById($id, $this->HotelModel);

        if (!$Hotel) {
            return response()->json(['message' => self::Hotel_NOT_FOUND_MESSAGE], 404);
        }

        return response()->json(new HotelResource($Hotel));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateHotelRequest $request, int $id): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $HotelData = $request->validated();
        $filePath = uploadFile($request, 'Hotel_files', 'file');
        if ($filePath) {
            $HotelData['file'] = $filePath;
        }
        $Hotel = $this->HotelService
            ->getHotelById($id, $this->HotelModel);

        if (!$Hotel) {

            return response()->json(['message' => self::Hotel_NOT_FOUND_MESSAGE], 404);
        }

        $this->HotelService->editHotel($Hotel, $HotelData);
        return response()->json([
            'message' => "Hotel a été modifié avec succée.",
            'data' =>  new HotelResource($Hotel)
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
        $Hotel = $this->HotelService
            ->getHotelById($id, $this->HotelModel);

        if (!$Hotel) {

            return response()->json(['message' => self::Hotel_NOT_FOUND_MESSAGE], 404);
        }

        $this->HotelService->deleteHotel($Hotel);

        return response()->json(['message' => "Hotel a été supprimé avec succée."], 202);
    }
}
