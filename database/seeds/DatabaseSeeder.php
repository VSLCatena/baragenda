<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        DB::table('users')->insert([
            'username' => 'netcie'
        ]);
        DB::table('users')->insert([
            'username' => 'test'
        ]);

        DB::table('infos')->insert([
            'id' => 1,
            'user_id' => 1,
            'objectGUID' => '123123',
            'lidnummer' => '12-345',
            'relatienummer' => '00112233445566',
            'name' => 'netcie',
            'email' => 'netcie@email.tld',
            'admin' => 1
        ]);

        DB::table('infos')->insert([
            'id' => 2,
            'user_id' => 2,
            'objectGUID' => '9871654',
            'lidnummer' => '12-412',
            'relatienummer' => '0022446688',
            'name' => 'test',
            'email' => 'test@email.tld',
            'admin' => 0
        ]);

        DB::table('committees')->insert([
            'name' => 'barco',
            'objectGUID' => '46541144'
        ]);
    }
}
