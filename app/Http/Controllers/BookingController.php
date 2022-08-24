<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Movie;
use App\Models\Cinema;
use App\Models\Seat;
use App\Models\AvailableSeat;

class BookingController extends Controller
{
    public function getCity(){
        $cities = City::select('*')
            ->get();
        $movies = Movie::select('id','movie_name')
            ->groupBy('movie_name')
            ->get();
        $seats = Seat::all();
        return view('system_booking', [
            'cities' => $cities,
            'movies' => $movies,
            'seats' => $seats
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
        ->select('id', 'Cinema_name', 'movie_id', 'city_id', 'show_time')
        ->get();
        if($cinemas){
            foreach($cinemas as $cinema){
                $cinema->show_time = date('Y-m-d h:i a', strtotime($cinema->show_time));
            }
        }
        return $cinemas;
    }

    public function getTotalSeats(Request $request){
        $city_id = $request->input('cityId');
        $movie_id = $request->input('movieId');
        $cinema_id = $request->input('cinemaId');
        $seats = Seat::all();
        $occupied_seats = Seat::where('seats.city_id', $city_id)
            ->where('seats.cinema_id', $cinema_id)
            ->leftJoin('available_seats', 'available_seats.seat_id', 'seats.id')
            ->where('available_seats.city_id', $city_id)
            ->where('available_seats.cinema_id', $cinema_id)
            ->select('seats.id', 'seats.seat_name')
            ->get();
        // dd($occupied_seats->toArray());
        $seat_arr =[];
        foreach($seats as $seat){
            $seat_log = substr($seat->seat_name, 0, 1);
            $seat_arr[$seat_log][]= $seat;
        }
        return  view('seats_available', [
            'seats' => $seat_arr,
            'occupied_seats' => $occupied_seats,
            'city_id' => $city_id,
            'movie_id' => $movie_id,
            'cinema_id' => $cinema_id
        ]);
    }

    public function getAvailableSeats(Request $request){
        $city_id = $request->input('city');
        $movie_id = $request->input('movie');
        $cinema_id = $request->input('cinema');
        $username = $request->input('username');
        foreach($request->checkbox as $seat_id){
            $available_seats = new AvailableSeat;
            $available_seats->seat_id = $seat_id;
            $available_seats->is_booked = 1;
            $available_seats->user_name = $username;
            $available_seats->movie_id = $movie_id;
            $available_seats->city_id = $city_id;
            $available_seats->cinema_id = $cinema_id;
            $available_seats->save();
        }
        return response()->json([
            'message' => 'Seats booked successfully.',
            'status' => 'success'
        ]);
    }
}
