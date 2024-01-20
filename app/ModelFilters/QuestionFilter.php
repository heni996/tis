<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class QuestionFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    /**
     * Filter questions by type.
     *
     * @param string $type
     * @return QuestionFilter
     */
    public function type($type)
    {
        return $this->where('type', 'like', "%$type%");
    }

    /**
     * Filter questions by content.
     *
     * @param string $content
     * @return QuestionFilter
     */
    public function content($content)
    {
        return $this->where('content', 'like', "%$content%");
    }
}
