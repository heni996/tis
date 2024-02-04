<?php

namespace App\Http\Controllers\FrontOffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Resposne\CreateResponseRequest;
use App\Http\Requests\Resposne\UpdateResponseRequest;
use App\Http\Resources\FrontOffice\ResponseResource;
use App\Http\Services\FrontOffice\ResponseService;
use App\Models\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResponseController extends Controller
{
    private ResponseService $ResponseService;
    private Response $ResponseModel;
    const Response_NOT_FOUND_MESSAGE = "Response non trouvé.";

    public function __construct(ResponseService $ResponseService)
    {
        $this->ResponseService = $ResponseService;
        $this->ResponseModel = new Response();
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        // if (!auth()->check()) {
        //     return response()->json(['error' => 'Unauthorized'], 403);
        // }
        $response = $this->ResponseService
            ->getResponses($this->ResponseModel, $request);
        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return JsonResponse
     */
    public function store(CreateResponseRequest $request): JsonResponse
    {
        // if (!auth()->check()) {
        //     return response()->json(['error' => 'Unauthorized'], 403);
        // }
        $ResponseData = $request->validated();
        $filePath = uploadFile($request, 'Response_files', 'file');
        if ($filePath) {
            $ResponseData['file'] = $filePath;
        }
        $Response = $this->ResponseService
            ->createResponse($ResponseData, $this->ResponseModel);
        return response()->json([
            'message' => "Response a été créé avec succès.",
            'data' => new ResponseResource($Response)
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
        // if (!auth()->check()) {
        //     return response()->json(['error' => 'Unauthorized'], 403);
        // }
        $Response = $this->ResponseService
            ->getResponseById($id, $this->ResponseModel);

        if (!$Response) {
            return response()->json(['message' => self::Response_NOT_FOUND_MESSAGE], 404);
        }

        return response()->json(new ResponseResource($Response));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateResponseRequest $request, int $id): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $ResponseData = $request->validated();
        $filePath = uploadFile($request, 'Response_files', 'file');
        if ($filePath) {
            $ResponseData['file'] = $filePath;
        }
        $Response = $this->ResponseService
            ->getResponseById($id, $this->ResponseModel);

        if (!$Response) {

            return response()->json(['message' => self::Response_NOT_FOUND_MESSAGE], 404);
        }

        $this->ResponseService->editResponse($Response, $ResponseData);
        return response()->json([
            'message' => "Response a été modifié avec succée.",
            'data' =>  new ResponseResource($Response)
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
        $Response = $this->ResponseService
            ->getResponseById($id, $this->ResponseModel);

        if (!$Response) {

            return response()->json(['message' => self::Response_NOT_FOUND_MESSAGE], 404);
        }

        $this->ResponseService->deleteResponse($Response);

        return response()->json(['message' => "Response a été supprimé avec succée."], 202);
    }
}
