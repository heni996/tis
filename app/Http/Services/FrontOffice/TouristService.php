<?php

namespace App\Http\Services\FrontOffice;

use App\Http\Resources\BackOffice\TouristResource;
use App\ModelFilters\TouristFilter;
use App\Models\Tourist;

class TouristService
{

    public function createTourist(array $data, Tourist $TouristModel): Tourist
    {
        return $TouristModel::create($this->TouristData($data));
    }


    public function editTourist(Tourist $Tourist, array $data): void
    {
        $Tourist->update($this->UpdateTouristData($data, $Tourist));
    }


    public function deleteTourist(Tourist $Tourist): void
    {
        $Tourist->delete();
    }


    public function getTouristById(int $id, Tourist $TouristModel)
    {
        return $TouristModel::where('id', $id)->first();
    }


    public function getTourists(Tourist $TouristModel, $request)
    {
        return $TouristModel::all();
    }


    public function TouristData($data): array
    {
        return [
            'first_name'=>$data['first_name'],
            'last_name'=>$data['last_name'],
            'nationality'=>$data['nationality'],
            'passport_number'=>$data['passport_number'],
            'is_famous'=>$data['is_famous'],
            'email'=>$data['email'],
            'arrival_date'=>$data['arrival_date'],
            'departure_date'=>$data['departure_date'],
            'code'=>$data['code'],
            'is_valid'=>$data['is_valid'],
        ];
    }
    public function UpdateTouristData($data, Tourist $Tourist): array
    {
        return [
            'first_name' => array_key_exists('first_name', $data) ? $data['first_name'] : $Tourist->first_name,
            'last_name' => array_key_exists('last_name', $data) ? $data['last_name'] : $Tourist->last_name,
            'nationality' => array_key_exists('nationality', $data) ? $data['nationality'] : $Tourist->nationality,
            'passport_number' => array_key_exists('passport_number', $data) ? $data['passport_number'] : $Tourist->passport_number,
            'is_famous' => array_key_exists('is_famous', $data) ? $data['is_famous'] : $Tourist->is_famous,
            'email' => array_key_exists('email', $data) ? $data['email'] : $Tourist->email,
            'arrival_date' => array_key_exists('arrival_date', $data) ? $data['arrival_date'] : $Tourist->arrival_date,
            'departure_date' => array_key_exists('departure_date', $data) ? $data['departure_date'] : $Tourist->departure_date,
            'code' => array_key_exists('code', $data) ? $data['code'] : $Tourist->code,
            'is_valid' => array_key_exists('is_valid', $data) ? $data['is_valid'] : $Tourist->is_valid,
        ];
    }
}
