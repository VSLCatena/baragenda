<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'id' ,'name',  'resource_id', 'generatedname','capacity','floorname','floorsection','features','email', 'created_at', 'updated_at'
	 ];
	 
    public function event(){
		return $this->hasMany('App\Models\Event');
    }
}
