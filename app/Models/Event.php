<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $casts = [
        'datetime_start' => 'datetime',
        'datetime_end' => 'datetime',
        'date_start' => 'datetime',
        'date_end' => 'datetime',
        'recurring_start' => 'datetime',
        'recurring_end' => 'datetime',
        'google_updated' => 'datetime',
        'google_created' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'summary','description','datetime_start','datetime_end','date_start','date_end','recurring_start','recurring_end','rrule','all_day','location','attendees','entrypoints','status','google_calendar_id','google_event_id','google_parent_event_id','google_updated','google_created','guests_caninviteothers','guests_canmodify','guests_canseeotherguests','organizer_displayname','creator_displayname','organizer_email','creator_email','htmllink','updated_by','committee_id'
	 ];

	public function user(){
		return $this->hasMany('App\Models\User');
    }
	public function location(){
		return $this->belongsTo('App\Models\Location');
    }
	public function shift(){
		return $this->hasMany('App\Models\Shift');
    }
	public function committee(){
		return $this->hasMany('App\Models\Committee');
    }
}
