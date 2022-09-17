<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;


use App\Helpers\MSGraphAPI\Group as MSGraphAPIGroup;
class AzureADSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:AzureADSync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'One-way sync of "leden" and "Commissies" to database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $msgraphapi = new MSGraphAPIGroup();
        $GroupCommissies = $msgraphapi->getGroupInfo("commissies");
        $GroupLeden = $msgraphapi->getGroupInfo("leden");

        $GroupLedenMembers =  $msgraphapi->getGroupMembers($GroupLeden[0]['id']);
        $GroupCommissiesMembers = $msgraphapi->getGroupMembers($GroupCommissies[0]['id']);

        //some sync stuff

    }
}
