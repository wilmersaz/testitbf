<?php

namespace Database\Seeders;

use App\Models\RoomType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoomTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RoomType::create(['type' => 'Estándar']);
        RoomType::create(['type' => 'Junior']);
        RoomType::create(['type' => 'Suite']);
    }
}
