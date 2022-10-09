<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommitteeNoteUser extends Model
{
    use HasFactory;


    public function info(){
		return $this->hasMany('App\Models\Info');
    }
	public function committee(){
		return $this->hasMany('App\Models\Committee');
    }
    public function updatedBy(){
		return $this->hasMany('App\Models\User');
    }
}

