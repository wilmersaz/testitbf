<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Accommodation extends Model
{

    protected $fillable = ['accommodation', 'room_type_id'];

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
