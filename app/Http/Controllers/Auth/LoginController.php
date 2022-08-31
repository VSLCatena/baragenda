<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;


use App\Models\User;
use App\Models\Info;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
class LoginController extends Controller
{
    // /*
    // |--------------------------------------------------------------------------
    // | Login Controller
    // |--------------------------------------------------------------------------
    // |
    // | This controller handles authenticating users for the application and
    // | redirecting them to your home screen.
    // |
    // */


    public function login(Request $request){
        if($request->isMethod('get'))
            return view('login');
        //debug login, username must exist in database, then you can login without Az
        // if(App::environment('local', 'dev')){
        //     if (User::with('info')->get()->contains('username', strtolower($request->username))){
        //         $user=User::where('username',$request->username)->first();
        //         Auth::login($user, true);
        //         return redirect(route('home'));
        //     }
        // }
        return Socialite::with('azure')->redirect(route('home'));
    }

    public function redirect(){
        return Socialite::driver('azure')->redirect();
    }

    public function callback() {
        $azureUser = Socialite::driver('azure')->user();

        $user = User::updateOrCreate(
            ['username' => Str::before($azureUser->email,'@')],
            ['token' => $azureUser->token]
        );

        /*     $azureUser = SocialiteProviders\Manager\OAuth2\User Object
        (
            [user] => Array
            (
                [displayName] => Martijn Koetsier
                [givenName] => Martijn
                [mail] => mkoetsier1@vslcatena.nl
                [surname] => Koetsier
                [id] => a93fd493-90b0-40fd-8d0e-b6678bb584bb
                )
                */
        $info = $user->info ?: new Info;
        $info->objectGUID = $azureUser->getId();
        $info->name = $azureUser->getName();
        $info->firstname = $azureUser->user['givenName'];
        $user->info()->save($info);

        Auth::login($user);
        return redirect('/home');
    }

    public function logout(Request $request)
    {
         Auth::guard()->logout();
         $request->session()->flush();
         $azureLogoutUrl = Socialite::driver('azure')->getLogoutUrl(route('home'));
         return redirect($azureLogoutUrl);
    }

}
