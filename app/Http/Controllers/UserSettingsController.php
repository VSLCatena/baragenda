<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use Illuminate\Support\Facades\Auth;
#use Laravel\Socialite\Facades\Socialite;
use App\Helpers\MSGraphAPI\Group as MSGraphAPIGroup;
use Illuminate\Support\Arr;
use App\Models\Committee;
use App\Models\Info;

use App\Http\Middelware\PrivilegeVerfier;

class UserSettingsController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    //when page is opeened
    public function changeSettings(Request $request ){
        if(Auth::user()->service_user){
            return redirect(route('home'));
        }
        //get request
        if($request->isMethod('get')){
            $info = Auth::user()->info;
            #$committees = $info->committee;
            #$comnames = Arr::join($committees->name,",");
            $committees = $info->committees->each(function ($c) { return $c->infos; } )->all(); #my committees & members
            $skills=$info->skills->each(function ($item) { return $item->committees;} )->all(); #my skills & committee
            return view('profile',array(
                'info' => $info,
                'skills' => $skills,
                'committees' => $committees
            ));
        }


        //else , should have explicit post requst
        $request->validate([
            'extra_info' => 'max:191',
        ]);

       //update settings
        $info = Auth::user()->info;
		$info->available=$request->input('available')[0] ? 1 : 0;
		$info->autofill_name=$request->input('autofill_name')[0] ? 1 : 0;
		$info->extra_info=$request->input('extra_info');
		$info->save();
        return redirect(route('home'))->with('info', 'Instellingen aangepast!');
    }
}
