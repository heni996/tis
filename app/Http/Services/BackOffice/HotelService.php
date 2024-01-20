<?php

namespace App\Http\Services\BackOffice;

// use App\Http\Resources\BackOffice\HotelResource;
// use App\ModelFilters\HotelFilter;
use App\Models\Hotel;

class HotelService
{

    public function createHotel(array $data, Hotel $HotelModel): Hotel
    {
        return $HotelModel::create($this->HotelData($data));
    }


    public function editHotel(Hotel $Hotel, array $data): void
    {
        $Hotel->update($this->UpdateHotelData($data, $Hotel));
    }


    public function deleteHotel(Hotel $Hotel): void
    {
        $Hotel->delete();
    }


    public function getHotelById(int $id, Hotel $HotelModel)
    {
        return $HotelModel::where('id', $id)->first();
    }


    public function getHotels(Hotel $HotelModel, $request)
    {
        // $records = getRecords($HotelModel, $request->all(), HotelResource::class, [], [], false, HotelFilter::class);
        return $HotelModel::all();
    }


    public function HotelData($data): array
    {
        return [
            'name'=>$data['name'],
            'user_id'=>$data['user_id'],
        ];
    }
    public function UpdateHotelData($data, Hotel $Hotel): array
    {
        $dataToUpdate = [
            'name' => array_key_exists('name', $data) ? $data['name'] :  $Hotel->name,
            'user_id' => array_key_exists('user_id', $data) ? $data['user_id'] :  $Hotel->user_id,
        ];

        return $dataToUpdate;
    }
}
