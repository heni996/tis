<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class HotelFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];
    /**
     * Filter hotels by name.
     *
     * @param string $name
     * @return HotelFilter
     */
    public function name($name)
    {
        return $this->where('name', 'like', "%$name%");
    }

    /**
     * Filter hotels by user ID.
     *
     * @param int $userId
     * @return HotelFilter
     */
    public function userId($userId)
    {
        return $this->where('user_id', $userId);
    }

    /**
     * Filter hotels by user's first name.
     *
     * @param string $firstName
     * @return HotelFilter
     */
    public function userFirstName($firstName)
    {
        return $this->whereHas('users', function ($query) use ($firstName) {
            $query->where('first_name', 'like', "%$firstName%");
        });
    }

    /**
     * Filter hotels by user's last name.
     *
     * @param string $lastName
     * @return HotelFilter
     */
    public function userLastName($lastName)
    {
        return $this->whereHas('users', function ($query) use ($lastName) {
            $query->where('last_name', 'like', "%$lastName%");
        });
    }
}
