<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tourist extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'nationality',
        'passport_number',
        'is_famous',
        'email',
        'arrival_date',
        'departure_date',
        'code',
        'is_valid',
    ];

    public function hotels()
    {
        return $this->belongsToMany(Hotel::class, 'hotel_tourist', 'tourist_id', 'hotel_id')
            ->withTimestamps();
    }
}
