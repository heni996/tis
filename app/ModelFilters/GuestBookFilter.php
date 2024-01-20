<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class GuestBookFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

     /**
     * Filter guest books by client's first name.
     *
     * @param string $clientFirstName
     * @return GuestBookFilter
     */
    public function clientFirstName($clientFirstName)
    {
        return $this->where('client_first_name', 'like', "%$clientFirstName%");
    }

    /**
     * Filter guest books by client's last name.
     *
     * @param string $clientLastName
     * @return GuestBookFilter
     */
    public function clientLastName($clientLastName)
    {
        return $this->where('client_last_name', 'like', "%$clientLastName%");
    }

    /**
     * Filter guest books by email.
     *
     * @param string $email
     * @return GuestBookFilter
     */
    public function email($email)
    {
        return $this->where('email', 'like', "%$email%");
    }

    /**
     * Filter guest books by phone number.
     *
     * @param string $phoneNumber
     * @return GuestBookFilter
     */
    public function phoneNumber($phoneNumber)
    {
        return $this->where('phone_number', 'like', "%$phoneNumber%");
    }

    /**
     * Filter guest books by country.
     *
     * @param string $country
     * @return GuestBookFilter
     */
    public function country($country)
    {
        return $this->where('country', 'like', "%$country%");
    }

    /**
     * Filter guest books by language.
     *
     * @param string $language
     * @return GuestBookFilter
     */
    public function language($language)
    {
        return $this->where('language', 'like', "%$language%");
    }
}
