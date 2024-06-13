<?php

namespace App\Models;

use App\Models\User;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dorm extends Model
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'images',
        'address',
        'longtitude',
        'latitude',
        'capacity',
        'type',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
