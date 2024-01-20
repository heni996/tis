<?php

namespace App\Http\Services\BackOffice;

use App\Http\Resources\BackOffice\ResponseResource;
use App\ModelFilters\ResponseFilter;
use App\Models\Response;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\AuthenticationException;

class ResponseService
{

    public function createResponse(array $data, Response $ResponseModel): Response
    {
        return $ResponseModel::create($this->ResponseData($data));
    }


    public function editResponse(Response $Response, array $data): void
    {
        $Response->update($this->UpdateResponseData($data, $Response));
    }


    public function deleteResponse(Response $Response): void
    {
        $Response->forceDelete();
    }


    public function getResponseById(int $id, Response $ResponseModel)
    {
        return $ResponseModel::where('id', $id)->first();
    }


    public function getResponses(Response $ResponseModel)
    {
        return $ResponseModel::all();
    }

    public function ResponseData($data): array
    {
        return [
            'value'=>$data['value'],
            'question_id'=>$data['question_id'],
            'guest_book_id'=>$data['guest_book_id'],
        ];
    }
    public function UpdateResponseData($data, Response $Response): array
    {
        return [
            'value' => isset($data['value']) ? $data['value'] : $Response->value,
            'question_id' => isset($data['question_id']) ? $data['question_id'] : $Response->question_id,
            'guest_book_id' => isset($data['guest_book_id']) ? $data['guest_book_id'] : $Response->guest_book_id,
        ];
    }
}
