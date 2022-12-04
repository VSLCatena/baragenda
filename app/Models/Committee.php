<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Committee extends Model
{
    use HasFactory;
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'objectGUID' ,'name','created_at', 'updated_at'
    ];
    public $table = "committees";

    public function infos()
    {
        return $this->belongsToMany(Info::class, 'committee_info', 'committee_id','info_id');
    }
    
    public function skill()
    {   
        return $this->hasMany('App\Models\Skill');
    }
    
    
    public function event()
    {   
        return $this->hasMany('App\Models\Event');
    }
    
    public function shifttype()
    {   
        return $this->hasMany('App\Models\ShiftType');
    }

}
