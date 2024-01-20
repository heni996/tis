<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class UserFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

     /**
     * Filter users by email.
     *
     * @param string $email
     * @return UserFilter
     */
    public function email($email)
    {
        return $this->where('email', 'like', "%$email%");
    }

    /**
     * Filter users by first name.
     *
     * @param string $firstName
     * @return UserFilter
     */
    public function firstName($firstName)
    {
        return $this->where('first_name', 'like', "%$firstName%");
    }

    /**
     * Filter users by last name.
     *
     * @param string $lastName
     * @return UserFilter
     */
    public function lastName($lastName)
    {
        return $this->where('last_name', 'like', "%$lastName%");
    }

    /**
     * Filter users by hotel ID.
     *
     * @param int $hotelId
     * @return UserFilter
     */
    public function hotelId($hotelId)
    {
        return $this->where('hotel_id', $hotelId);
    }

    /**
     * Filter users by role.
     *
     * @param string $role
     * @return UserFilter
     */
    public function role($role)
    {
        return $this->whereHas('roles', function ($query) use ($role) {
            $query->where('name', $role);
        });
    }

}
