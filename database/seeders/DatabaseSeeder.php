<?php

#namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


use App\Models\Committee;
use App\Models\CommitteeNoteUser;
use App\Models\Event;
use App\Models\Info;
use App\Models\Location;
use App\Models\Shift;
use App\Models\ShiftType;
use App\Models\Skill;
use App\Models\User;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
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
        if(env('APP_ENV') == 'local'){
            $this->command->info('-- Cleanup Database --');
            $this->barRandomSeederCleanup();
        }
        $this->command->info('-- Starting Basic seeder --');
        $this->barMinimumSeeder();
        $this->command->info('-- Starting Randomized seeder --');
        $this->barRandomSeeder();
    }

    private function barRandomSeederCleanup(){
        Schema::disableForeignKeyConstraints();
        DB::table('committees')->truncate();
        DB::table('committee_note_users')->truncate();
        DB::table('events')->truncate();
        DB::table('infos')->truncate();
        DB::table('locations')->truncate();
        DB::table('shifts')->truncate();
        DB::table('shift_types')->truncate();
        DB::table('info_shift')->truncate();
        DB::table('skills')->truncate();
        DB::table('users')->truncate();
        Schema::enableForeignKeyConstraints();
    }

    private function barMinimumSeeder(){
        DB::table('users')->updateOrInsert(
            ['id' => '10'],
            ['username' => 'google'
        ]);
        DB::table('infos')->updateOrInsert(
            ['objectGUID' => '900913', 'user_id' => 10],
            [
                'user_id' => 10,
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
    private function barRandomSeeder(){
        $this->AmountTotalUsers=200;
        $this->RemoveUserPercentage=85;
        $this->AmountShiftTypes=20;
        $this->AmountShifts=50;

        $faker = Faker\Factory::create();
        # Committee
        # Event
        # Info
        # Location
        # Shift
        # ShiftType
        # Skill
        # User

        $this->command->info('- Creating 15 Committees');
        $Committees = Committee::factory()
            ->count(15)
            ->create();
            $this->command->info('- Creating 5 Locations');
            $Events = Location::factory()
            ->count(5)
            ->create();

            $this->command->info('- Creating 30 Events');
            $Events = Event::factory()
            ->count(30)
            ->create();

            $this->command->info('- Creating ' . $this->AmountTotalUsers . ' Info (of users) and corresponding users');
            $Infos = Info::factory()
            ->count($this->AmountTotalUsers)
            ->create();

            $this->command->info('- Removing ' .$this->RemoveUserPercentage. ' % of all users (like they never logged in), so we keep:'.((1-($this->RemoveUserPercentage/100)) * $this->AmountTotalUsers));
            $removeInfos=$Infos->random(($Infos->count()/100)*$this->RemoveUserPercentage)->each(function ($infoItem){
                $userid=$infoItem->user->id;
                $infoItem->update(['user_id' => NULL]); #this way we keep Info model but dont have a User model attached to it.
                User::destroy($userid);
            });


            $userInfoList = $Infos->whereNotNull('user_id');

            $this->command->info('- Attach (some) users to committees');
            foreach ($userInfoList as $user) {
                for($j=0;$j<Arr::random([0,1,2,3]);$j++){
                    $user->committee()->attach($Committees->random()); #add each (active) user to 0-3 committees
                }
            }

        $this->command->info('- Creating 20 Skills');
        for($i=0; $i<20 ; $i++) {
            $Skills = Skill::factory()
                ->for($Committees->random())
                ->create();
        }

        $this->command->info('- Creating ' . $this->AmountShiftTypes . ' ShiftTypes');
        for($i=0; $i<$this->AmountShiftTypes; $i++) {
            $ShiftTypes = ShiftType::factory()
            ->for($Committees->random())
            ->for($userInfoList->random()->user)
            ->create();
        }

        $this->command->info('- Creating ' . $this->AmountShifts . ' Shifts');
        for($i=0; $i<$this->AmountShifts ; $i++) {
            $Shifts = Shift::factory()
                ->for(ShiftType::get()->random())
                ->state([
                    'updated_by' => $userInfoList->random()->user,
                ])
                ->create();
            $this->command->info('>>> For each shift assign 0,1,2 people');
            for($j=0;$j<Arr::random([0,1,2]);$j++){
                $Shifts->shiftuser()->attach($Infos->random());
            }

        }
        $this->command->info('- Creating notes about users(=infos)');
        foreach($Infos->random(rand(5, $Infos->count()*0.25)) as $user){
            $notes = CommitteeNoteUser::factory()
            ->state([
                'committee_id' => $Committees->random()->id,
                'info_id' => $user->id,
                'updated_by' => $userInfoList->random()->user()->first(),
            ])
            ->create();
        };

    }

}
