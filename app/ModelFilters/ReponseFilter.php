<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class ReponseFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

     /**
     * Filter responses by value.
     *
     * @param string $value
     * @return ResponseFilter
     */
    public function value($value)
    {
        return $this->where('value', 'like', "%$value%");
    }

    /**
     * Filter responses by question ID.
     *
     * @param int $questionId
     * @return ResponseFilter
     */
    public function questionId($questionId)
    {
        return $this->where('question_id', $questionId);
    }

    /**
     * Filter responses by guest book ID.
     *
     * @param int $guestBookId
     * @return ResponseFilter
     */
    public function guestBookId($guestBookId)
    {
        return $this->where('guest_book_id', $guestBookId);
    }

    /**
     * Filter responses by question type.
     *
     * @param string $questionType
     * @return ResponseFilter
     */
    public function questionType($questionType)
    {
        return $this->whereHas('question', function ($query) use ($questionType) {
            $query->where('type', 'like', "%$questionType%");
        });
    }
}
