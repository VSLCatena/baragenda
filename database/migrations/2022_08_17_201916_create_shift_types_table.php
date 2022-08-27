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
        Schema::create('shift_types', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('enabled')->default(false); //meteen ingeschakeld?
            $table->integer('common')->default(false); //komt deze dagelijks voor?
            $table->integer('committee_id')->unsigned()->nullable(); //barco
            $table->string('title')->nullable(); //uitzit
            $table->string('description')->nullable(); //bardienst van 22:00-laat
            $table->datetime('default_datetime')->nullable(); // 22:00
            $table->datetime('default_datetime_end')->nullable(); // laat
			$table->integer('updated_by')->unsigned();
			$table->timestamps();

			#foreign references
			$table->foreign('committee_id')->references('id')->on('committees');
			$table->foreign('updated_by')->references('id')->on('users');

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
        Schema::dropIfExists('shift_types');
    }
};
