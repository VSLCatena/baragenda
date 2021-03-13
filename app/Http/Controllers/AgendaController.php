<?php
namespace App\Http\Controllers;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use RRule\RRule;


class AgendaController extends Controller
{
    /**
     * Creates a range of dates. 
     * 
     *  
     *   [20200823] => Array
     *   (
     *       [carbon] => Carbon\Carbon Object
     *           (
     *               [date] => 2020-08-23 00:00:00.000000
     *               [timezone_type] => 3
     *               [timezone] => UTC
     *           )
     * 
     *        [events] => Array
     *
	 * @param \Carbon $start_date
	 * @param \Carbon $end_date
	 * @param \Boolean $firstday
	 * @param \Boolean $prepArray
	 * @param \String $prepArrayFormat
	 * @param \String $nameArray
	 *
	 * @return array
	 */
	private function generateDateRange(Carbon $start_date, Carbon $end_date, $firstday=false,$prepArray=false,$prepArrayFormat='Ymd',$nameArray='events')
	{
        

        //range starting from first day of that week till last day of that week
		if($firstday){
			$start_date =  new Carbon("Monday $start_date");
			$end_date =  new Carbon("Sunday $end_date");
		}
		$dates = []; //empty array init
        //loop over every day
		for($date = $start_date->copy(); $date->lte($end_date); $date->addDay()) {
			$dates[] =  new Carbon($date); //creat carbon obj
        }
        //prepare the array for a specific format
        if($prepArray){
            $array=array();
            foreach($dates as $k=>$d){
                $array[$d->format($prepArrayFormat)]=array('carbon'=>$d,$nameArray=>array());
            }
            $dates=$array;
        }

		return $dates;
    }

    private function calculateShape(Carbon $start, Carbon $end): Array {
        //for each event calculate pos/size for display
            $eventFormat=array(
                'pos'=>round(Carbon::parse($start)->startOfDay()->diffInMinutes(Carbon::parse($start))/1440,3),
                'pos_day'=>round((intval($start->format('N'))-1)/7,2),
                'size'=>round(Carbon::parse($start)->diffInMinutes(Carbon::parse($end))/1440,3)
            );
            $eventFormat['size_day']=$eventFormat['size'] >7 ? 1 :  round((intval(Carbon::parse($end)->format('N'))-1)/7,2);
            return $eventFormat;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   $date = $request ? Carbon::parse($request->date,"UTC") : Carbon::parse("today","UTC");
        $start = $date->copy()->startOfWeek();
        $end = $start->copy()->endOfMonth();
        $dates = $this->getRangeEvents($start,$end); //get me range of dates between
        return view('agenda',array(
            'events'=>$dates,
            'selectedDate'=>$date
            ));
    }



    /**
     * Get all events
     *
     * @return \Illuminate\Http\Response
     */
    public function getRangeEvents($start,$end)
    {
        $dates=$this->generateDateRange($start,$end,true,true); // give me an array of dates!!!!
        $events =  Event::whereBetween('datetime_start', [$start, $end])->orWhereBetween('datetime_end',[$start,$end])->get(); //retrieve all values 
        $merged = $this->mergeEventsDateRange($events,$dates);
        #echo("<pre>");print_r($merged);die;
        return $merged;


    }


/**
     * Format the dates.
     * @param array  $eventsArray
     * @return \Illuminate\Http\Response
     */
    private function mergeEventsDateRange(object $events, $dates): Array
    {   
        $events->each(function ($e) use(&$dates) {
            $source = $e->google_calendar_id == env('GOOGLE_CALENDAR_ID_PRIVATE') ? env('GSUITE_CAL_PRIV_SUMM_DISPLAY') : env('GSUITE_CAL_PUBL_SUMM_DISPLAY');
            $shape=$this->calculateShape($e->datetime_start,$e->datetime_end);
            $dates[$e->datetime_start->format('Ymd')]['events'][]=array('source'=>$source,'shape'=>$shape,'object'=>$e);
        });
    
            // //if startdate is IN this period
            // if (array_key_exists($date,$dates)) {
            //     $dates[$date]['events']=$eventList; //fill the events in the dates
            // }
            // //go through each event.
            // else {
            //     foreach($eventList as $i=>$event){
            //         #print_r(Carbon::parse($event['start']['carbon'])->format('N'));
            //         if($event['start']['carbon']->between($start,$end)) {
            //             //print_r($event['summary'] . "startdate is in week"); //can never happen due to above
            //         }
            //         if($event['end']['carbon']->between($start,$end)) {
            //             //print_r($event['summary'] . " enddate of period is in this week"); //event of lastweek until somewhere this week
            //             $event['shape']['pos_day']=0; //where to start on a all day event
            //             $event['shape']['size_day']=$event['shape']['size'] >7 ? 1 :  round((intval(Carbon::parse($event['end']['carbon'])->format('N'))-1)/7,2);  //where to end on a all day event
            //             $dates[$start->format('Ymd')]['events'][]=$event; // append events to array with specific format
            //         }
            //         elseif(reset($dates)['carbon']->between($event['start']['carbon'],$event['end']['carbon'])) {
            //             //print_r($event['summary'] . "monday  is in period"); // event of lastweek until somewhere next week
            //             $event['shape']['pos_day']=0;
            //             $event['shape']['size_day']=1;
            //             $dates[$start->format('Ymd')]['events'][]=$event;
            //         }
            //     }
            // }


        
        // foreach($events as $k =>$event) {
        //     $carbon=$event->googleEvent->start->date ? Carbon::parse($event->googleEvent->start->date) : Carbon::parse($event->googleEvent->start->dateTime);
        //     $displayname=$event->googleEvent->organizer->displayName;

        //     $attendees['resource']=array();
        //     $attendees['guest']=array();
        //     foreach($event->googleEvent->attendees as $k=>$a){
        //         $a->resource==1 ? $attendees['resource'][] = $a->email : $attendees['guest'][] = $a->email;
        //     }
            // $eventFormat = array(
            //     'summary'=>$event->googleEvent->summary,
            //     'calendar'=> $calendar,
            //     'calendarNo'=>$calendarNo,
            //     'description'=>$event->googleEvent->description,
            //     'id'=>$event->googleEvent->id,
            //     'location'=>$event->googleEvent->location,
            //     'start'=>array(
            //         'dateTime'=>$event->googleEvent->start->dateTime,
            //         'date'=>$event->googleEvent->start->date,
            //         'carbon'=>$event->googleEvent->start->date ? Carbon::parse($event->googleEvent->start->date) : Carbon::parse($event->googleEvent->start->dateTime)),
            //     'end'=>array(
            //         'dateTime'=>$event->googleEvent->end->dateTime,
            //         'date'=>$event->googleEvent->end->date,
            //         'carbon'=>$event->googleEvent->end->date ? Carbon::parse($event->googleEvent->end->date) : Carbon::parse($event->googleEvent->end->dateTime)),
            //     'interval'=>null,
            //     'attendees'=>$attendees
            //     );

            // if(!$minimal){
            //     $arr = array(
            //         'created'=>Carbon::parse($event->googleEvent->created),
            //         'guestsCanInviteOthers'=>$event->googleEvent->guestsCanInviteOthers,
            //         'guestsCanModify'=>$event->googleEvent->guestsCanModify,
            //         'guestsCanSeeOtherGuests'=>$event->googleEvent->guestsCanSeeOtherGuests,
            //         'htmlLink'=>$event->googleEvent->htmlLink,

            //         'recurrence'=>$event->googleEvent->recurrence,
            //         'recurringEventId'=>$event->googleEvent->recurringEventId,
            //         'status'=>$event->googleEvent->status,
            //         'updated'=>$event->googleEvent->updated,
            //         'creator'=>array(
            //             'displayName'=>$event->googleEvent->creator->displayName,
            //             'email'=>$event->googleEvent->creator->email),
            //         'organizer'=>array(
            //             'displayName'=>$event->googleEvent->organizer->displayName,
            //             'email'=>$event->googleEvent->organizer->email)
            //         );
            //         $eventFormat+=$arr;
            // }
            //if multiday event (diff datetime >1), then expand array by interval and insert self event. Also ingore default insert by 'continue'
            //  if(Carbon::parse($eventFormat['end']['carbon'])->diffInDays(Carbon::parse($eventFormat['start']['carbon']))>1) {
            //     $interval = $this->generateDateRange(Carbon::parse($event->googleEvent->start->date),Carbon::parse($event->googleEvent->end->date),false,false); //create interval
            //    $eventFormat['interval']=$interval; // this or below... still need to figure out..

            // }




            // $eventsArray[$carbon->format('Ymd')][]=$eventFormat;

        
        #echo("<pre>");print_r($event);die;
        return $dates;
    }
    //function should send less data (this function is for Ajax Post requests) , might be replaced with PHP version of index
    public function getdate(Request $request){
        $request->validate(['s' => 'regex:/^[a-zA-Z0-9 :()+]+$/']);
        $event = Event::where('google_event_id', $request->google_event_id)->first(); //raw event
        return response()->json(array('data'=>$event));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //public function edit($id=null)
    //{
     //
    //}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }


}
