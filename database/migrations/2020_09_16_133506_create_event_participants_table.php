<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_participants', function (Blueprint $table) {
            $table->id();
            $table->double('nota_3',2,2)->default(0);
            $table->double("nota_7",2,2)->default(0);
            $table->string("src", 100)->nullable();
            $table->unsignedBigInteger("event_id");
            $table->unsignedBigInteger("user_id");
            $table->smallInteger("status")->default(0);
            $table->timestamps();

            $table->foreign("user_id")->references("id")->on("users");
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
        Schema::dropIfExists('event_participants');
    }
}
