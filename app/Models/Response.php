<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    use HasFactory;

    protected $fillable = [
        'value',
        'question_id',
        'guest_book_id',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function guestBook()
    {
        return $this->belongsTo(GuestBook::class);
    }
}
