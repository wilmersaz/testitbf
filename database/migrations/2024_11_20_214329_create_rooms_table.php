<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade')->comment('Relación con hoteles');
            $table->foreignId('room_type_id')->constrained()->onDelete('cascade')->comment('Relación con tipos de habitación');
            $table->foreignId('accommodation_id')->constrained()->onDelete('cascade')->comment('Relación con acomodaciones');
            $table->integer('quantity')->comment('cantidad de habitaciones');
            $table->timestamps();

            // Validación de que no existan habitaciones duplicadas para el mismo hotel
            $table->unique(['hotel_id', 'room_type_id', 'accommodation_id', 'quantity']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
