<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
	/**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'datetime_start',
        'datetime_end',
        'recurring_start',
        'recurring_end',
        'google_updated',
        'google_created',
        'updated_at',
        'deleted_at'
    ];
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'summary','description','datetime_start','datetime_end','recurring_start','recurring_end','rrule','all_day','location','attendees','entrypoints','status','google_calendar_id','google_event_id','google_parent_event_id','google_updated','google_created','guests_caninviteothers','guests_canmodify','guests_canseeotherguests','organizer_displayname','creator_displayname','organizer_email','creator_email','htmllink','updated_by','committee_id'
	 ];
	 
	public function getUser(){
		return $this->hasMany('App\Models\User');
    }
	public function getLocation(){
		return $this->hasMany('App\Models\Location');
    }
	public function getShift(){
		return $this->hasMany('App\Models\Shift');
    }
	public function getCommittee(){
		return $this->hasMany('App\Models\Committee');
    }
}
