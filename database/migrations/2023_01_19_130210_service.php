<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Service extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service', function (Blueprint $table) {
            $table->bigIncrements('id_service');
            $table->string('nom');
            $table->string('description');
            $table->string('image');
            $table->string('type');
            $table->unsignedBigInteger('id_hotel'); // Modifier la colonne pour qu'elle soit non signée
            $table->foreign('id_hotel')
                ->references('id')
                ->on('hotel')
                ->onDelete('restrict')
                ->onUpdate('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service');
    }
}
