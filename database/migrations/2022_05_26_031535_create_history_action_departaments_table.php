<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryActionDepartamentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_action_departaments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('departament_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->tinyInteger('action');
            $table->json('data')->nullable();
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
        Schema::dropIfExists('history_action_departaments');
    }
}
