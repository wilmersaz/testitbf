<?php

namespace Database\Seeders;

use App\Models\Hotel;
use Illuminate\Database\Seeder;

class HotelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hotels = [
            ['name' => 'Hotel Norte', 'tax_id_number' => '123456789', 'city' => 'BOGOTÁ. D.C. - BOGOTÁ. D.C.', 'address' => 'calle 1', 'max_rooms' => 50],
            ['name' => 'Hotel Sur', 'tax_id_number' => '987654321', 'city' => 'BARRANQUILLA - ATLÁNTICO', 'address' => 'calle 2', 'max_rooms' => 30],
            ['name' => 'Hotel Oriente', 'tax_id_number' => '456789123', 'city' => '	MEDELLÍN - ANTIOQUIA', 'address' => 'calle 3', 'max_rooms' => 40],
            ['name' => 'Hotel Occidente', 'tax_id_number' => '789123456', 'city' => 'CARTAGENA DE INDIAS - BOLÍVAR', 'address' => 'calle 4', 'max_rooms' => 20],
            ['name' => 'Hotel Centro', 'tax_id_number' => '321654987', 'city' => 'SANTA MARTA - MAGDALENA', 'address' => 'calle 5', 'max_rooms' => 60]
        ];

        foreach ($hotels as $hotel) {
            Hotel::create($hotel);
        }
    }
}
