<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleOneTapController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/google-signin', [GoogleOneTapController::class, 'handleGoogleSignIn'])->name('google.signin');

Route::get('/logout', function () {
    session()->flush();
    return redirect('/');
})->name('logout');
