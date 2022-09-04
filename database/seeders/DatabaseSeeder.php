<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);


        DB::table('users')->updateOrInsert(
            ['id' => '900913'],
            ['username' => 'google'
        ]);
        DB::table('infos')->updateOrInsert(
            ['objectGUID' => '900913', 'user_id' => 900913],
            [
                'user_id' => 900913,
                'objectGUID' => '900913',
                'lidnummer' => '900913',
                'relatienummer' => '900913',
                'firstname' => 'Google',
                'name' => 'GoogleSync',
                'email' => '',
                'admin' => 0
            ]);


        DB::table('committees')->updateOrInsert([
            'name' => 'barco',
            'objectGUID' => '46541144'
        ]);
    }
}
