<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestBook extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_first_name',
        'client_last_name',
        'email',
        'phone_number',
        'extra_comment',
        'hotel_id',
        'language',
        'country',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}
