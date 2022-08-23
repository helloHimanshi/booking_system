<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Movie;
use App\Models\Cinema;

class BookingController extends Controller
{
    public function getCity(){
        $cities = City::select('*')
            ->get();
        $movies = Movie::select('id','movie_name')
            ->groupBy('movie_name')
            ->get();
        return view('system_booking', [
            'cities' => $cities,
            'movies' => $movies
        ]);
    }

    public function getMovie(Request $request){
        $city_id = $request->input('cityId');
        $movies = Movie::where('City_id', $city_id) 
        ->where('isReleased', 1)
        ->get();
        return $movies;
    }

    public function getCinema(Request $request){
        $city_id = $request->input('cityId');
        $movie_id = $request->input('movieId');
        $cinemas = Cinema::where('movie_id', $movie_id)
        ->where('city_id', $city_id)
        ->select('*')
        ->get();
        return view('book_show', [
            'cinemas' => $cinemas
        ]);
        // return redirect()->route('/book_show', ['cinemas' => $cinemas]);
    }

    public function getAvailableSeats(Request $request){
        $city_id = $request->input('cityId');
        $movie_id = $request->input('movieId');
        $cinema_id = $request->input('cinemaId');
        dd($request->all());
    }
}
