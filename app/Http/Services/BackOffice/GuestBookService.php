<?php

namespace App\Http\Services\BackOffice;

use App\Http\Resources\BackOffice\GuestBookResource;
use App\ModelFilters\GuestBookFilter;
// use App\ModelFilters\GuestBookFilter;
use App\Models\GuestBook;

class GuestBookService
{

    public function createGuestBook(array $data, GuestBook $GuestBookModel): GuestBook
    {
        return $GuestBookModel::create($this->GuestBookData($data));
    }


    public function editGuestBook(GuestBook $GuestBook, array $data): void
    {
        $GuestBook->update($this->UpdateGuestBookData($data, $GuestBook));
    }


    public function deleteGuestBook(GuestBook $GuestBook): void
    {
        $GuestBook->delete();
    }


    public function getGuestBookById(int $id, GuestBook $GuestBookModel)
    {
        return $GuestBookModel::where('id', $id)->first();
    }


    public function getGuestBooks(GuestBook $GuestBookModel, $request)
    {
        return getRecords($GuestBookModel, $request->all(), GuestBookResource::class, [], [], false, GuestBookFilter::class);
    }


    public function GuestBookData($data): array
    {
        return [
            'client_first_name' => $data['client_first_name'],
            'client_last_name'=>$data['client_last_name'],
            'email'=>$data['email'],
            'phone_number'=>$data['phone_number'],
            'extra_comment'=>$data['extra_comment'],
            'hotel_id'=>$data['hotel_id'],
            'language'=>$data['language'],
            'country'=>$data['country'],
        ];
    }

    public function UpdateGuestBookData($data, GuestBook $GuestBook): array
    {
        return [
            'client_first_name' => array_key_exists('client_first_name', $data) ? $data['client_first_name'] : $GuestBook->client_first_name,
            'client_last_name' => array_key_exists('client_last_name', $data) ? $data['client_last_name'] : $GuestBook->client_last_name,
            'email' => array_key_exists('email', $data) ? $data['email'] : $GuestBook->email,
            'phone_number' => array_key_exists('phone_number', $data) ? $data['phone_number'] : $GuestBook->phone_number,
            'extra_comment' => array_key_exists('extra_comment', $data) ? $data['extra_comment'] : $GuestBook->extra_comment,
            'hotel_id' => array_key_exists('hotel_id', $data) ? $data['hotel_id'] : $GuestBook->hotel_id,
            'language' => array_key_exists('language', $data) ? $data['language'] : $GuestBook->language,
            'country' => array_key_exists('country', $data) ? $data['country'] : $GuestBook->country,
        ];
    }
}
