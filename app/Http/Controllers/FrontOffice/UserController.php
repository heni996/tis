<?php

namespace App\Http\Controllers\FrontOffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\FrontOffice\UserResource;
use App\Http\Services\FrontOffice\UserService;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private UserService $UserService;
    private User $UserModel;
    const User_NOT_FOUND_MESSAGE = "User non trouvé.";

    public function __construct(UserService $UserService)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $this->UserService = $UserService;
        $this->UserModel = new User();
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
        $response = $this->UserService
            ->getUsers($this->UserModel, $request);
        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return JsonResponse
     */
    public function store(CreateUserRequest $request): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $UserData = $request->validated();
        $filePath = uploadFile($request, 'User_files', 'file');
        if ($filePath) {
            $UserData['file'] = $filePath;
        }
        $User = $this->UserService
            ->createUser($UserData, $this->UserModel);
        return response()->json([
            'message' => "User a été créé avec succès.",
            'data' => new UserResource($User)
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
        $User = $this->UserService
            ->getUserById($id, $this->UserModel);

        if (!$User) {
            return response()->json(['message' => self::User_NOT_FOUND_MESSAGE], 404);
        }

        return response()->json(new UserResource($User));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $UserData = $request->validated();
        $filePath = uploadFile($request, 'User_files', 'file');
        if ($filePath) {
            $UserData['file'] = $filePath;
        }
        $User = $this->UserService
            ->getUserById($id, $this->UserModel);

        if (!$User) {

            return response()->json(['message' => self::User_NOT_FOUND_MESSAGE], 404);
        }

        $this->UserService->editUser($User, $UserData);
        return response()->json([
            'message' => "User a été modifié avec succée.",
            'data' =>  new UserResource($User)
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
        $User = $this->UserService
            ->getUserById($id, $this->UserModel);

        if (!$User) {

            return response()->json(['message' => self::User_NOT_FOUND_MESSAGE], 404);
        }

        $this->UserService->deleteUser($User);

        return response()->json(['message' => "User a été supprimé avec succée."], 202);
    }
}
