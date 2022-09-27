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
        Schema::create('committee_info', function (Blueprint $table) {
            $table->integer('committee_id')->unsigned();
            $table->integer('info_id')->unsigned();
            #foreign references
            $table->foreign('committee_id')->references('id')->on('committees');
            $table->foreign('info_id')->references('id')->on('infos');

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
        Schema::dropIfExists('committee_info');
    }
};
