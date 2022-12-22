<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle(){
        return Socialite::driver('google')->stateless()->redirect();
    }
    public function handleGoogleCallback(){

    try {
        $user = Socialite::driver('google')->user();
        // dd($user);
        $finduser = User::where('google_id', $user->getId())->first();
        if($finduser){
            Auth::login($finduser);
            // dd($finduser->all());
            return redirect()->intended('home');
        }
        else{
            $newUser = User::create([
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'google_id' => $user->getId(),
                'password' => Hash::make(123123),
            ]);
            Auth::login($newUser);
            return redirect()->intended('home');
        }
    } catch (\Throwable $th) {
        throw $th;
    }
    
    }
}
