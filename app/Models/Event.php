<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'shift_id','title','description','datetime_start','datetime_end','recurring_start','recurring_end','rrule','all_day','location_id','committee_id','attendees','status','google_calendar_id','google_event_id','google_parent_event_id','google_updated','updated_by'
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
