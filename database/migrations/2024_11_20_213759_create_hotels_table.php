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
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('Nombre del hotel, debe ser único');
            $table->string('tax_id_number')->comment('número de identificación tributario');
            $table->string('address')->comment('Dirección del hotel');
            $table->string('city')->comment('Ciudad (Obtenida de la divipola)');
            $table->integer('max_rooms')->comment('Máximo de habitaciones permitidas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
