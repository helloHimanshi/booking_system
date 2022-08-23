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

Route::get('/system_booking', [BookingController::class, 'getCity']);
Route::get('/get_movie', [BookingController::class, 'getMovie']);
Route::get('/get_cinema', [BookingController::class, 'getCinema']);
Route::get('/book_show/{id?}', function () {
    return view('book_show');
})->name('book_show');
Route::get('/select_seat', function () {
    return redirect('/booking/dashboard');
});
Route::view('/booking/dashboard', 'seat_booking');