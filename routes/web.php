<?php

use App\Http\Controllers\GithubController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/login' , [UserController::class, 'login'])->name('login');
Route::get('/auth/google/', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback/', [GoogleController::class, 'HandleGoogleCallback'])->name('google.callback');

Route::controller(GithubController::class)->group(function(){
    Route::get('auth/github','redirectToGithub')->name('github.login');
    Route::get('auth/github/callback' , 'handleGithubCallback');
});
