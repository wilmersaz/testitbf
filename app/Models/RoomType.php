<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{

    protected $fillable = ['type'];

    public function accommodations()
    {
        return $this->hasMany(Accommodation::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}