<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('summary');
            $table->text('description')->nullable();
            $table->dateTime('datetime_start')->nullable();
            $table->dateTime('datetime_end')->nullable();
            $table->dateTime('date_start')->nullable();
            $table->dateTime('date_end')->nullable();
            $table->dateTime('recurring_start')->nullable();
            $table->dateTime('recurring_end')->nullable();
			$table->string('rrule')->nullable();
            $table->boolean('all_day')->default(false);
            $table->string('location')->nullable();
			$table->json('attendees')->nullable();
			$table->json('entrypoints')->nullable(); //Google Meet
			$table->enum('status',['draft','published','deleted']); //draft published or deleted
            $table->string('google_calendar_id'); //external calendar
            $table->string('google_event_id'); //external event
            $table->string('google_parent_event_id')->nullable();
            $table->dateTime('google_updated')->nullable(); 
            $table->dateTime('google_created')->nullable(); 
            $table->boolean('guests_caninviteothers')->default(false);
            $table->boolean('guests_canmodify')->default(false);
            $table->boolean('guests_canseeotherguests')->default(false);
            $table->string('organizer_displayname')->nullable();
            $table->string('creator_displayname')->nullable();
            $table->string('organizer_email')->nullable();
            $table->string('creator_email')->nullable();
            $table->string('htmllink',255)->nullable(); 
            
            $table->integer('committee_id')->unsigned()->nullable();
            $table->integer('shift_id')->unsigned()->nullable(); //either shift or reservations
            
            $table->integer('updated_by')->unsigned();
            $table->timestamps();
            
			#foreign references
			$table->foreign('shift_id')->references('id')->on('shifts');
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
        Schema::dropIfExists('events');
    }
}
