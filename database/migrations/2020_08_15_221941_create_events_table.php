<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string("title", 150);
            $table->string('slug', 250);
            $table->string("short_link", 50);
            $table->string("description", 255)->nullable();
            $table->integer('hours')->default(0);
            //type
            $table->enum('type', ['asistencia', 'aprobacion' ,'asistencia_aprobacion']);
            //fechas
            $table->date("f_inicio");
            $table->date("f_fin");
            $table->date("matricula_inicio");
            $table->date("matricula_fin");

            $table->smallInteger("status")->default(1);

            $table->unsignedBigInteger('sponsor_id');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('sponsor_id')->references('id')->on('sponsors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
