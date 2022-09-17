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
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('remember_token', 'token');
        });
        //separate due to error by manipulation on non-existing column
        Schema::table('users', function (Blueprint $table) {
            $table->string('token', 2560)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('token', 'remember_token');
            $table->string('token', 2560)->change();
        });
    }
};
