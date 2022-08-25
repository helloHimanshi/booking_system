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
    /**
     * Fetchs list of cities.
     *
     */
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

    /**
     * Fetchs list of movies displayed in the selected city.
     *
     */
    public function getMovie(Request $request){
        $city_id = $request->input('cityId');
        $movies = Movie::where('City_id', $city_id) 
        ->where('isReleased', 1)
        ->get();
        return $movies;
    }

    /**
     * lists of cinemas running the selected movie show for the selected city.
     *
     */
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

    /**
     * List of total seats in the selected cinema hall.
     *
     */
    public function getTotalSeats(Request $request){
        $city_id = $request->input('cityId');
        $movie_id = $request->input('movieId');
        $cinema_id = $request->input('cinemaId');
        $seats = Seat::where('seats.city_id', $city_id)
            ->where('seats.cinema_id', $cinema_id)
            ->leftJoin('available_seats', 'available_seats.seat_id', 'seats.id')
            ->select('available_seats.seat_id', 'available_seats.id' ,'seats.*')
            ->orderBy('seats.seat_name')
            ->get();
        $seat_arr =[];
        foreach($seats as $seat){
            $seat_log = substr($seat->seat_name, 0, 1);
            $seat_arr[$seat_log][]= $seat;
        }
        return  view('seats_available', [
            'seats' => $seat_arr,
            'city_id' => $city_id,
            'movie_id' => $movie_id,
            'cinema_id' => $cinema_id
        ]);
    }

    /**
     * Records the booked seats.
     *
     */
    public function getAvailableSeats(Request $request){
        $validated = $request->validate([
            'city' => 'required',
            'movie' => 'required',
            'cinema' => 'required',
            'username' => 'required',
            'checkbox' => 'required|array|min:1'
        ],
        [
            'checkbox.required' => 'Could not processed with your request as atleast one seat is required to be selected for booking show.'
        ]);
          
        $city_id = $request->input('city');
        $movie_id = $request->input('movie');
        $cinema_id = $request->input('cinema');
        $username = $request->input('username');
        $seats_selected = [];
        foreach($request->checkbox as $seat_id){
            $available_seats = new AvailableSeat;
            $available_seats->seat_id = $seat_id;
            $available_seats->is_booked = 1;
            $available_seats->user_name = $username;
            $available_seats->movie_id = $movie_id;
            $available_seats->city_id = $city_id;
            $available_seats->cinema_id = $cinema_id;
            $seat_occupied = Seat::where('id', $seat_id)
                ->value('seat_name');
            array_push($seats_selected, $seat_occupied);
            $available_seats->save();
        }
        $movie = Movie::where('id', $movie_id)
            ->value('movie_name');
        $cinema = Cinema::where('id', $cinema_id)
            ->select('Cinema_name', 'show_time')
            ->first();
        $city = City::where('id', $city_id)
            ->value('name');
        return view('ticket_dashboard', [
            'city' => $city,
            'movie' => $movie,
            'cinema' => $cinema,
            'username' => $username,
            'seats_selected' => $seats_selected
        ]);
    }
}
