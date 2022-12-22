<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GithubController extends Controller
{
    public function redirectToGithub(){
        return Socialite::driver('github')->redirect();
    }

    public function handleGithubCallback(){
        try {
            $user = Socialite::driver('github')->stateless()->user();
            // dd($user);
            $find_user = Socialite::where('email',$user->email)->first();
            if($find_user){
                Auth::login($find_user);
                $user->session()->regenerate();
                return redirect(route('home'));
            }else{
                $github_user = User::updateOrCreate([
                    'github_id' => $user->getId(),
                ],[
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'github_token' => $user->token,
                    'github_refresh_token' => $user->refreshToken,
                    'password' => Hash::make(123123),
                ]);
        
                Auth::login($github_user);
                $user->session()->regenerate();
                return redirect(route('home'));
            }           
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
