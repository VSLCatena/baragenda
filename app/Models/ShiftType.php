<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftType extends Model
{
    use HasFactory;

    protected $fillable = [
        'enabled','common', 'committee_id', 'title','description','default_datetime','default_datetime_end','updated_by'
    ];
    public $table = "shift_types";
	public function shift()
    {
        return $this->hasMany(Shift::class);
    }
	public function committee()
    {
        return $this->belongsTo(Committee::class);
    }

	public function user()
    {
        return $this->belongsTo(User::class,"updated_by");
    }
}
