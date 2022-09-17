<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;


use App\Helpers\MSGraphAPI\Group as MSGraphAPIGroup;
use Illuminate\Support\Arr;
use App\Models\Committee;
use App\Models\Info;
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
        $GroupCommittee = $msgraphapi->getGroupInfo("commissies"); #only group itself
        $GroupCommittee_Members = $msgraphapi->getGroupMembers($GroupCommittee[0]['id']);

        $GroupLeden = $msgraphapi->getGroupInfo("leden"); #only group itself
        $GroupLedenMembers = $msgraphapi->getGroupMembers($GroupLeden[0]['id']);

        #create users vs committees array
        $users=array();
        foreach ($GroupCommittee_Members['members'] as $key => $Committee) {
            if($Committee['objectType'] == "user") {continue;}
            $CommitteeMembers = $msgraphapi->getGroupMembers($Committee['id']);
            foreach ($CommitteeMembers['members'] as $key => $member) {
                if(!(array_key_exists($member['id'],$users))){
                    $users[$member['id']]=array('id'=>$member['id']);
                }
                $users[$member['id']]['committee'][]=array(
                    "displayName"=>$Committee['displayName'],
                    "id"=>$Committee['id']
                );
            }
        }


        # add committees
        foreach($GroupCommittee_Members['members'] as $Committee) {
            Committee::updateOrCreate(
                ['objectGUID'=> $Committee['id']],
                ['name'=> $Committee['displayName']
            ]);
        }

        # add members
        foreach($GroupLedenMembers['members'] as $userInfo) {
            $userInfoObj = Info::updateOrCreate(
                ['objectGUID'   => $userInfo['id']],
                ['lidnummer'=> $userInfo['employeeNumber'],
                'relatienummer'=> $userInfo['employeeId'],
                'name'  => $userInfo['displayName'],
                'firstname'    => $userInfo['givenName'],
                'email'    => $userInfo['mail'],
            ]);
            if(array_key_exists($userInfo['id'],$users)){
                foreach($users[$userInfo['id']]['committee'] as $c){
                    $committeeObj=Committee::where('objectGUID',$c['id'])->first();
                    $userInfoObj->committee()->attach($committeeObj);
                }
            }
        }
    }
}
