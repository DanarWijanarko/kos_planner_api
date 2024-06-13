<?php

namespace App\Models;

use App\Models\Dorm;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'dorm_id',
        'room_number',
        'room_type',
        'facilities',
        'description',
        'images',
        'price',
        'available',
    ];

    public function dorm()
    {
        return $this->belongsTo(Dorm::class, 'dorm_id');
    }
}
