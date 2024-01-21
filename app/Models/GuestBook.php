<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class GuestBook extends Model
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
        'client_first_name',
        'client_last_name',
        'email',
        'phone_number',
        'extra_comment',
        'hotel_id',
        'language',
        'country',
    ];

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

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

}
