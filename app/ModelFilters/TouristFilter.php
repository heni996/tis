<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class TouristFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    /**
     * Filter tourists by first name.
     *
     * @param string $firstName
     * @return TouristFilter
     */
    public function firstName($firstName)
    {
        return $this->where('first_name', 'like', "%$firstName%");
    }

    /**
     * Filter tourists by last name.
     *
     * @param string $lastName
     * @return TouristFilter
     */
    public function lastName($lastName)
    {
        return $this->where('last_name', 'like', "%$lastName%");
    }

    /**
     * Filter tourists by nationality.
     *
     * @param string $nationality
     * @return TouristFilter
     */
    public function nationality($nationality)
    {
        return $this->where('nationality', 'like', "%$nationality%");
    }

    /**
     * Filter tourists by passport number.
     *
     * @param string $passportNumber
     * @return TouristFilter
     */
    public function passportNumber($passportNumber)
    {
        return $this->where('passport_number', 'like', "%$passportNumber%");
    }

    /**
     * Filter tourists by famous status.
     *
     * @param bool $isFamous
     * @return TouristFilter
     */
    public function isFamous($isFamous)
    {
        return $this->where('is_famous', $isFamous);
    }

    /**
     * Filter tourists by email.
     *
     * @param string $email
     * @return TouristFilter
     */
    public function email($email)
    {
        return $this->where('email', 'like', "%$email%");
    }

    /**
     * Filter tourists by arrival date.
     *
     * @param string $arrivalDate
     * @return TouristFilter
     */
    public function arrivalDate($arrivalDate)
    {
        return $this->where('arrival_date', 'like', "%$arrivalDate%");
    }

    /**
     * Filter tourists by departure date.
     *
     * @param string $departureDate
     * @return TouristFilter
     */
    public function departureDate($departureDate)
    {
        return $this->where('departure_date', 'like', "%$departureDate%");
    }

    /**
     * Filter tourists by code.
     *
     * @param string $code
     * @return TouristFilter
     */
    public function code($code)
    {
        return $this->where('code', 'like', "%$code%");
    }

    /**
     * Filter tourists by validity status.
     *
     * @param bool $isValid
     * @return TouristFilter
     */
    public function isValid($isValid)
    {
        return $this->where('is_valid', $isValid);
    }

    /**
     * Filter tourists by hotel.
     *
     * @param int $hotelId
     * @return TouristFilter
     */
    public function hotel($hotelId)
    {
        return $this->whereHas('hotels', function ($query) use ($hotelId) {
            $query->where('hotel_id', $hotelId);
        });
    }
}
