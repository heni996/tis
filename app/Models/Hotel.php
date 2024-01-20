<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
    ];

    /**
     * Define a many-to-many relationship with the User model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'hotel_user', 'hotel_id', 'user_id');
    }

    public function guestBooks()
    {
        return $this->hasMany(GuestBook::class);
    }

}
