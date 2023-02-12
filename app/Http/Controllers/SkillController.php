<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\Skill;
use App\Models\Info;

class SkillController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('privilege');
    }

    public function index(Request $request)
    {
        //TODO: zoek uit hoe je alle commissies opvraagt
        $skills = Skill::all();

        $infos = Info::where('id', '>=', 100)->with('skills')->get();

        //All Self-Skills
        $selfSkills=Auth::user()->info->skills->each(function ($item) { return $item;} )->all();
        // $skills=$info->skills->each(function ($item) { return $item->committees;} )->all(); #my skills & committee

        //All Self-Committee-Skills
        $selfCommitteeSkills=Auth::user()->info->committees->each(function ($item) {
            return $item->skills;
        } )->all(); #my skills & committee

        return view('skills', compact('skills'),array(
			'selfSkills'=>$selfSkills,
            'selfCommitteeSkills'=>$selfCommitteeSkills,
            'infos'=>$infos
			));
    }
}
