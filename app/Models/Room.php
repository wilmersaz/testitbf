<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ['hotel_id', 'room_type_id', 'accommodation_id', 'quantity'];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function accommodation()
    {
        return $this->belongsTo(Accommodation::class);
    }
}
