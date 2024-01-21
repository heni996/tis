<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Str;

class Hotel extends Model
{
    use HasFactory, HasUuids;

    /**
     * Indicates the primary key type.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

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

    public function tourists()
    {
        return $this->hasMany(Tourist::class, 'hotel_tourist', '');
    }

    /**
     * Boot function to handle UUID generation.
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }
}
