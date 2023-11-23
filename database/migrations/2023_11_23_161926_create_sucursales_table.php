<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sucursales', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            // Puedes agregar más columnas según tus necesidades
            $table->timestamps();
        });

        // Agrega las unidades de negocio
        DB::table('sucursales')->insert([
            ['nombre' => 'El Jumillano'],
            ['nombre' => 'Lavazza'],
            ['nombre' => 'Impacto Positivo'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sucursales');
    }
};
