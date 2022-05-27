<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['Aguardando Confirmação', 'Confirmado', 'Cancelado', 'Atendido']);
            $table->dateTime('datetime');
            $table->boolean('compareceu')->default(false);
            $table->bigInteger('address_id')->unsigned();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->bigInteger('operator_id')->unsigned()->nullable();
            $table->bigInteger('service_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('address_id')->references('id')->on('addresses');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('operator_id')->references('id')->on('operators');
            $table->foreign('service_id')->references('id')->on('services');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointments');
    }
}
