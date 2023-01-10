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
        Schema::create('info_skill', function (Blueprint $table) {
            $table->integer('info_id')->unsigned();
            $table->integer('skill_id')->unsigned();
            #foreign references
            $table->foreign('info_id')->references('id')->on('infos');
            $table->foreign('skill_id')->references('id')->on('skills');
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('info_skill');
    }
};
