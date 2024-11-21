<?php

namespace Database\Seeders;

use App\Models\Accommodation;
use Illuminate\Database\Seeder;

class AccommodationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Accommodation::create(['accommodation' => 'Sencilla', 'room_type_id' => 1]);
        Accommodation::create(['accommodation' => 'Sencilla', 'room_type_id' => 3]);
        Accommodation::create(['accommodation' => 'Doble', 'room_type_id' => 1]);
        Accommodation::create(['accommodation' => 'Doble', 'room_type_id' => 3]);
        Accommodation::create(['accommodation' => 'Triple', 'room_type_id' => 2]);
        Accommodation::create(['accommodation' => 'Triple', 'room_type_id' => 3]);
        Accommodation::create(['accommodation' => 'Cuádruple', 'room_type_id' => 2]);
    }
}
