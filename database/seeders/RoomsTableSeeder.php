<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoomsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Room::create(['hotel_id' => 1, 'room_type_id' => 1, 'accommodation_id' => 1, 'quantity' => 30]);
        Room::create(['hotel_id' => 1, 'room_type_id' => 3, 'accommodation_id' => 4, 'quantity' => 15]);
        Room::create(['hotel_id' => 1, 'room_type_id' => 2, 'accommodation_id' => 5, 'quantity' => 5]);
    }
}
