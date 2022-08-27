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
        Schema::create('shift_user', function (Blueprint $table) {
            $table->integer('shift_id')->unsigned(); //bardienst van 22:00-laat (uitzit)
            $table->integer('user_id')->unsigned(); //persoon X

			#foreign references
			$table->foreign('shift_id')->references('id')->on('shifts');
			$table->foreign('user_id')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('shift_user');
    }
};
