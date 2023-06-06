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
        Schema::create('seguidores', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->integer('usuario_envia')->unsigned();;
            $table->integer('usuario_recibe')->unsigned();;
            $table->integer('fecha_amistad');
            $table->timestamps();

            $table->foreign('usuario_envia')
                ->references('id')
                ->on('usuarios')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('usuario_recibe')
                ->references('id')
                ->on('usuarios')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seguidores');
    }
};
