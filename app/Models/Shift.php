<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


use Illuminate\Database\Eloquent\SoftDeletes;

class Shift extends Model
{
    use HasFactory;

    use SoftDeletes;
	public $table = "shifts";

    public function updated_by(){
		return $this->belongsTo('App\Models\User','updated_by');
    }

    public function shifttype(){
		return $this->belongsTo('App\Models\ShiftType','shift_type_id');
    }
    public function shiftuser(){
		return $this->belongsToMany(Info::class,'info_shift');
    }

}
