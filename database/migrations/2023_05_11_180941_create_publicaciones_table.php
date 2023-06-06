<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publicaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('autor')->unsigned();
            $table->string('descripcion', 255);
            $table->string('lugar_realizacion', 255);
            $table->string('licencia', 255);
            $table->integer('camara')->unsigned();
            $table->string('imagen', 255);
            $table->integer('num_reacciones')->unsigned()->nullable();
            $table->integer('album')->unsigned();
            $table->date('fecha_public');
            $table->timestamps();

            $table->foreign('autor')->references('id')->on('usuarios');
            $table->foreign('camara')->references('id')->on('camaras');
            $table->foreign('album')->references('id')->on('albumes');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('publicaciones');
    }
};
