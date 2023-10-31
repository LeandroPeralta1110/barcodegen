<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCodigosBarrasTable extends Migration
{
    public function up()
    {
        Schema::create('codigos_barras', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_barras')->unique();
            $table->unsignedBigInteger('usuario_id');
            $table->timestamps();

            $table->foreign('usuario_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('codigos_barras');
    }
}
