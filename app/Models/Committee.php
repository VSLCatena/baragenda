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


    public function info()
    {
        return $this->belongsToMany('App\Models\Info');
    }
}
