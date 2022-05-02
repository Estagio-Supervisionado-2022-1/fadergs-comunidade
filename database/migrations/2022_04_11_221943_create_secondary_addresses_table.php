<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSecondaryAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('secondary_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('building_number', 8);
            $table->string('floor', 2)->nullable();
            $table->string('room', 50)->nullable();
            $table->string('description', 100)->nullable();
            $table->bigInteger('address_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('address_id')
                    ->references('id')
                    ->on('addresses')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {   
        Schema::dropIfExists('secondary_address');
    }
}
