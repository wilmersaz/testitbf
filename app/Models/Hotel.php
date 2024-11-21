<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    protected $fillable = ['name', 'tax_id_number', 'address', 'city', 'max_rooms'];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
