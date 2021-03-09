<?php

namespace App\Console\Commands;

use Spatie\GoogleCalendar\Event as GEvent;
use Spatie\GoogleCalendar\Resource;
use App\Models\Event as LEvent;
use App\Models\Location;
use Carbon\Carbon;
use RRule\RRule;
use Illuminate\Console\Command;

class CalendarSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservation:CalendarSync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {


        #id & timestamp
       
        #get local $array1 = array("green", "red", "blue");
        $events_local = LEvent::whereBetween('datetime_start', [Carbon::today()->startOfDay(), Carbon::today()->addMonths(1)])->get();
        if(!($events_local->isEmpty())){
            $id_local = $events_local->mapWithKeys(function ($item) {
                return  array($item->google_event_id=>$item);
            });
        } else{ $id_local=collect(); }

        #get remote $array2 = array("green", "yellow", "red");
        $events_remote = GEvent::get(Carbon::today()->startOfDay(), Carbon::today()->addMonths(1));
        $id_remote = $events_remote->mapWithKeys(function ($item) {
            return  array($item->id=>$item);
        });
        #print_r($events_remote->take(3));
        #print_r($id_remote->all());
        #print_r($events_remote->first());
        
        $id_left=$id_local->diffKeys($id_remote); #$id_left=array_values(array_diff($id_local,$id_remote)); #$array("blue"); 
        $id_intersect=$id_local->intersectByKeys($id_remote); #$id_intersect=array_values(array_intersect($id_local,$id_remote)) # intersect $array("green",  "red"); 
        $id_right=$id_remote->diffKeys($id_local); #$id_right=array_values(array_diff($id_remote,$id_local));  #$array("yellow");
        #print_r($id_left);
 
        # if id remote==id local
        if($id_intersect->count() > 0){
            $id_intersect->each(function ($item, $key) {
            #   check timestamp
            #   if local is newer-> sync to google (update publish)
            # delete if delete 
            #   if remote is newer-> do nothing???
                if($item->updated_at > $item->google_updated) {
                    if($item->status != 'publish') {
                    #delete remote
                    }
                    else {
                    #update event
                    }
                }
            
            });
            # if only id local
        }
        if($id_left->count() > 0){
            $id_left->each(function ($item, $key) {
            #   create (if not delete)
                if($item.status == 'publish'){
                  #create
                }
            });
        }
        # if only id remote 
        if($id_right->count() > 0){
            $id_right->each(function ($item, $key) {
               #sync to local
                $event = LEvent::create([

                    'title'                 =>  $item->summary,
                    'description'           =>  $item->description,
                    'datetime_start'        =>  Carbon::parse($item->startDateTime)->format('Y-m-d H:i:s'),
                    'datetime_end'          =>  Carbon::parse($item->endDateTime)->format('Y-m-d H:i:s'),
                    'recurring_start'       =>  null, #for parent
                    'recurring_end'         =>  null,  #for parent
                    'rrule'                 =>  $item->recurringEventId ? GEvent::find($item->recurringEventId,env('GOOGLE_CALENDAR_ID_PUBLIC'))->recurrence[0] : null,
                    'all_day'               =>  $item->isAllDayEvent(),
                    'location'           =>  null, #$item.atrendees.email in location.email ? location.id : item.loctionid
                    'committee_id'          =>  null,
                    'attendees'             =>  json_encode($item->attendees),
                    'status'                => 'published',
                    'google_calendar_id'    =>  env('GOOGLE_CALENDAR_ID_PUBLIC'), #$item->getCalendarId(),
                    'google_event_id'       =>  $item->id,
                    'google_parent_event_id'=>  $item->recurringEventId,
                    'google_updated'        =>  Carbon::parse($item->updated)->format('Y-m-d H:i:s'),
                    'updated_by'            =>  900913,
                ]);
                $event->save();
            });
        } 

        $Resources = Resource::get();
        foreach($Resources as $item) {
            $location = Location::updateOrCreate(
                ['resource_id'   => $item['id']],
                [ 
                'name'          => $item['name'], 
                'generatedname' => $item['generatedname'], 
                'capacity'      => $item['capacity'],
                'floorname'     => $item['floorname'], 
                'floorsection'  => $item['floorsection'],  
                'features'      => json_encode($item['features']), 
                'email'         => $item['email'], 

            ]);
        }


        return 0;
    }
}
