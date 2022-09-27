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
        if($request->isMethod('get')){
            return view('login');
        }
        if($request->isMethod('post')){
            if(env('APP_ENV') == 'local' && env('APP_OFFLINE')==1 ){
                    $user=User::where('username',env('APP_DEBUG_USERNAME'))->first();
                    Auth::login($user, false);
                    return redirect(route('home'));
            }
        } else {
            return Socialite::driver('azure')->with([
                'prompt'        => 'select_account',
                'whr'           =>'vslcatena.nl',
                'domain_hint'   =>'vslcatena.nl'
                ])->redirect(route('home'));
        }
    }

    public function callback() {
        $azureUser = Socialite::driver('azure')->user();

        $user = User::updateOrCreate(
            ['username' => Str::before($azureUser->email,'@')],
            ['token' => $azureUser->token]
        );

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
