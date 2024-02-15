<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Tourist extends Model
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
        'image'
    ];

    public function hotels()
    {
        return $this->belongsToMany(Hotel::class, 'hotel_tourist');
    }

     /**
     * Get hotels data from the pivot table.
     *
     * @return array
     */
    protected function hotelsData(): array
    {
        return $this->hotels->map(function ($hotel) {
            return [
                'hotel_id' => $hotel->pivot->hotel_id,
                'custom_column' => $hotel->pivot->custom_column, // Replace with actual column names
                // Add other columns as needed
            ];
        })->toArray();
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
