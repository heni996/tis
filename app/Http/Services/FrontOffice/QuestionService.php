<?php

namespace App\Http\Services\FrontOffice;

use App\Http\Resources\BackOffice\QuestionResource;
use App\Models\Question;
use App\ModelFilters\QuestionFilter;
use App\Models\ConfigQuestion;
use App\Models\User;

class QuestionService
{

    public function createQuestion(array $data, Question $QuestionModel): Question
    {
        return $QuestionModel::create($this->QuestionData($data));
    }


    public function editQuestion(Question $Question, array $data): void
    {
        $Question->update($this->UpdateQuestionData($data, $Question));
    }


    public function deleteQuestion(Question $Question): void
    {
        $Question->forceDelete();
    }


    public function getQuestionById(int $id, Question $QuestionModel)
    {
        return $QuestionModel::where('id', $id)->first();
    }


    public function getQuestions(Question $QuestionModel)
    {
        return $QuestionModel::all();
    }

    public function QuestionData($data): array
    {
        return [
            'type'=>$data['type'],
            'content'=>$data['content'],
        ];
    }
    public function UpdateQuestionData($data, Question $Question): array
    {
        return [
            'type' => isset($data['type']) ? $data['type'] : $Question->type,
            'content' => isset($data['content']) ? $data['content'] : $Question->content,
        ];
    }
}
