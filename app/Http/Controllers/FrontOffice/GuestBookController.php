<?php

namespace App\Http\Controllers\FrontOffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\GuestBook\CreateGuestBookRequest;
use App\Http\Requests\GuestBook\UpdateGuestBookRequest;
use App\Http\Resources\FrontOffice\GuestBookResource;
use App\Http\Services\FrontOffice\GuestBookService;
use App\Models\GuestBook;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GuestBookController extends Controller
{
    private GuestBookService $GuestBookService;
    private GuestBook $GuestBookModel;
    const GuestBook_NOT_FOUND_MESSAGE = "GuestBook non trouvé.";

    public function __construct(GuestBookService $GuestBookService)
    {
        $this->GuestBookService = $GuestBookService;
        $this->GuestBookModel = new GuestBook();
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
        $response = $this->GuestBookService
            ->getGuestBooks($this->GuestBookModel, $request);
        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return JsonResponse
     */
    public function store(CreateGuestBookRequest $request): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $GuestBookData = $request->validated();
        $filePath = uploadFile($request, 'GuestBook_files', 'file');
        if ($filePath) {
            $GuestBookData['file'] = $filePath;
        }
        $GuestBook = $this->GuestBookService
            ->createGuestBook($GuestBookData, $this->GuestBookModel);
        return response()->json([
            'message' => "GuestBook a été créé avec succès.",
            'data' => new GuestBookResource($GuestBook)
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
        $GuestBook = $this->GuestBookService
            ->getGuestBookById($id, $this->GuestBookModel);

        if (!$GuestBook) {
            return response()->json(['message' => self::GuestBook_NOT_FOUND_MESSAGE], 404);
        }

        return response()->json(new GuestBookResource($GuestBook));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateGuestBookRequest $request, int $id): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $GuestBookData = $request->validated();
        $filePath = uploadFile($request, 'GuestBook_files', 'file');
        if ($filePath) {
            $GuestBookData['file'] = $filePath;
        }
        $GuestBook = $this->GuestBookService
            ->getGuestBookById($id, $this->GuestBookModel);

        if (!$GuestBook) {

            return response()->json(['message' => self::GuestBook_NOT_FOUND_MESSAGE], 404);
        }

        $this->GuestBookService->editGuestBook($GuestBook, $GuestBookData);
        return response()->json([
            'message' => "GuestBook a été modifié avec succée.",
            'data' =>  new GuestBookResource($GuestBook)
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
        $GuestBook = $this->GuestBookService
            ->getGuestBookById($id, $this->GuestBookModel);

        if (!$GuestBook) {

            return response()->json(['message' => self::GuestBook_NOT_FOUND_MESSAGE], 404);
        }

        $this->GuestBookService->deleteGuestBook($GuestBook);

        return response()->json(['message' => "GuestBook a été supprimé avec succée."], 202);
    }
}
