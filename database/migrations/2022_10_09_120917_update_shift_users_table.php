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
        Schema::table('shift_user', function (Blueprint $table) {
            $table->renameColumn('user_id', 'info_id');
			#foreign references
            $table->foreign('info_id')->references('id')->on('infos');
            $table->dropForeign('shift_user_user_id_foreign');
        });
        Schema::rename("shift_user", "info_shift");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename("info_shift", "shift_user");
        Schema::table('shift_user', function (Blueprint $table) {
            $table->dropForeign('shift_user_info_id_foreign');
            $table->renameColumn('info_id', 'user_id');
            #foreign references
            $table->foreign('user_id')->references('id')->on('users');
        });
    }
};

