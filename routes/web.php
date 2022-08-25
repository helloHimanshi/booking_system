<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BookingController;
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

/**
* Apis for booking movie.
*
*/
Route::get('/system_booking', [BookingController::class, 'getCity']);
Route::get('/get_movie', [BookingController::class, 'getMovie']);
Route::get('/get_cinema', [BookingController::class, 'getCinema']);
Route::get('/book_show/{id?}', function () {
    return view('book_show');
})->name('book_show');
Route::get('/select_seat', [BookingController::class, 'getTotalSeats']);
Route::group(['middleware' => 'prevent-back-history'],function(){
	Auth::routes();
    Route::post('/select_seat', [BookingController::class, 'getAvailableSeats']);
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

