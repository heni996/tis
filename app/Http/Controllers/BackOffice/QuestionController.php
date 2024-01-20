<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Question\CreateQuestionRequest;
use App\Http\Requests\Question\UpdateQuestionRequest;
use App\Http\Resources\BackOffice\QuestionResource;
use App\Http\Services\BackOffice\QuestionService;
use App\Models\Question;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    private QuestionService $QuestionService;
    private Question $QuestionModel;
    const Question_NOT_FOUND_MESSAGE = "Question non trouvé.";

    public function __construct(QuestionService $QuestionService)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $this->QuestionService = $QuestionService;
        $this->QuestionModel = new Question();
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
        $response = $this->QuestionService
            ->getQuestions($this->QuestionModel, $request);
        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return JsonResponse
     */
    public function store(CreateQuestionRequest $request): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $QuestionData = $request->validated();
        $filePath = uploadFile($request, 'Question_files', 'file');
        if ($filePath) {
            $QuestionData['file'] = $filePath;
        }
        $Question = $this->QuestionService
            ->createQuestion($QuestionData, $this->QuestionModel);
        return response()->json([
            'message' => "Question a été créé avec succès.",
            'data' => new QuestionResource($Question)
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
        $Question = $this->QuestionService
            ->getQuestionById($id, $this->QuestionModel);

        if (!$Question) {
            return response()->json(['message' => self::Question_NOT_FOUND_MESSAGE], 404);
        }

        return response()->json(new QuestionResource($Question));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateQuestionRequest $request, int $id): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $QuestionData = $request->validated();
        $filePath = uploadFile($request, 'Question_files', 'file');
        if ($filePath) {
            $QuestionData['file'] = $filePath;
        }
        $Question = $this->QuestionService
            ->getQuestionById($id, $this->QuestionModel);

        if (!$Question) {

            return response()->json(['message' => self::Question_NOT_FOUND_MESSAGE], 404);
        }

        $this->QuestionService->editQuestion($Question, $QuestionData);
        return response()->json([
            'message' => "Question a été modifié avec succée.",
            'data' =>  new QuestionResource($Question)
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
        $Question = $this->QuestionService
            ->getQuestionById($id, $this->QuestionModel);

        if (!$Question) {

            return response()->json(['message' => self::Question_NOT_FOUND_MESSAGE], 404);
        }

        $this->QuestionService->deleteQuestion($Question);

        return response()->json(['message' => "Question a été supprimé avec succée."], 202);
    }
}
