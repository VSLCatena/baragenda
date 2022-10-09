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
        Schema::table('committee_note_users', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->renameColumn('user_id', 'info_id');
			#foreign references
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
        Schema::table('committee_note_users', function (Blueprint $table) {
            $table->dropForeign('committee_note_users_info_id_foreign');
            $table->renameColumn('info_id', 'user_id');
            #foreign references
            $table->foreign('user_id')->references('id')->on('users');
        });
    }
};
