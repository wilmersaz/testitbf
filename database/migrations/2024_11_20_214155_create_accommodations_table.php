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
        Schema::create('accommodations', function (Blueprint $table) {
            $table->id();
            $table->string('accommodation')->comment('Acomodación (Sencilla, Doble, Triple, Cuádruple)');
            $table->foreignId('room_type_id')->constrained()->onDelete('cascade')->comment('Relación con tipos de habitación');
            $table->timestamps();

            // Validación de que no existan acomodaciones duplicadas junto con el tipo de habitación
            $table->unique(['accommodation', 'room_type_id']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accommodations');
    }
};
