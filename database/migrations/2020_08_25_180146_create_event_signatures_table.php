<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventSignaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_signatures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("signature_id");
            $table->unsignedBigInteger("event_id");

            $table->foreign("signature_id")->references("id")->on("signatures");
            $table->foreign("event_id")->references("id")->on("events");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_signatures');
    }
}
