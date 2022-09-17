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
        Schema::create('user_committee', function (Blueprint $table) {
            $table->integer('committee_id')->unsigned();
            $table->integer('user_id')->unsigned();
            #foreign references
            $table->foreign('committee_id')->references('id')->on('committees');
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
        Schema::dropIfExists('user_committee');
    }
};
