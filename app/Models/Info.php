<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


use Database\Factories\InfoFactory;
class Info extends Model
{
    use HasFactory;

	 /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
           'objectGUID','lidnummer','relatienummer','name','email','available','autofill_name','extra_info','groups','admin'
        ];

    public $table = "infos";
    /**
     * Get the user that belongs to userinfo.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function committees()
    {
        return $this->belongsToMany(Committee::class, 'committee_info', 'info_id', 'committee_id');
    }
}
