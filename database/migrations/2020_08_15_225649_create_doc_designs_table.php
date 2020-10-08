<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocDesignsTable extends Migration
{
    public function up()
    {
        Schema::create('doc_designs', function (Blueprint $table) {
            $table->id();
            $table->string("sponsor");
            $table->string("otorga", 50)->default("Otorga el presente");
            $table->string("certificado", 50)->default("CERTIFICADO");
            $table->string("sponsor_logo", 100)->nullable();
            $table->json("signatures");
            $table->text("description");
            $table->date("date")->nullable();
            $table->smallInteger('show_date')->default(1);

            $table->unsignedBigInteger("event_id");
            $table->timestamps();

            $table->foreign('event_id')->references("id")->on("events");
        });
    }

    public function down()
    {
        Schema::dropIfExists('doc_designs');
    }
}
