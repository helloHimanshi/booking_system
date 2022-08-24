<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>System Booking</title>
        <!-- CSS only -->
        <link href="{{ URL::asset('css/system_booking.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ URL::asset('css/seat_booking.css') }}" rel="stylesheet" type="text/css">
        <!-- Bootstrap CDN -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    </head>
    <body>
    <div class="form">
      <div class="mt-4 mb-4 title">Book Your Tickets</div>
      <div class="mt-4 mb-4">
      <label class="subtitle">Select City</label>
            <select id="city_id" class="input">
                <option value="" disabled selected>Select City</option>
                @if($cities)
                    @foreach($cities as $city)
                        <option value="{{$city->id}}">
                            {{$city->name}}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="mt-4 mb-4">
        <label class="subtitle">Select Movie</label>
        <div id="movie_id" class="d-flex justify-content-around">
            @if($movies)
                @foreach($movies as $movie)
                <div value="{{$movie->id}}" class="input" onClick="confirm('Please Select the city first.')">
                        {{$movie->movie_name}}
                </div>
                @endforeach
            @endif
        </div>
        </div>
        <div class="mt-2 mb-4">
            <div id="cinema_id" value="" class="input" style="display:none;">
            </div>
        </div>
        <div class="mt-2">
            <div id="available_seat_count" class="input" style="display:none;"> 
        </div>
    </div>
    </body>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script>
        let checkMovies;
        let bookMovie;
        $(document).ready(function() {
            checkMovies =  function (movieId, cityId){
            console.log(movieId, cityId);
            $('#cinema_id').hide();
            $.ajax({
                type:'GET',
                url:"{{ url('/get_cinema') }}",
                data:{
                    'movieId': movieId,
                    'cityId': cityId
                },
                success:function(data){
                    
            $('#cinema_id').show();
                    let content = '';
                    $.each(data, function(i, item) {
                        console.log(item);
                            content += ` ${item.Cinema_name}
                            </br>
                            ${item.show_time}
                            </br>
                            <button type="button" 
                            class="btn btn-sm btn-light mt-2 mb-2" 
                            onClick="bookMovie(${item.id}, ${item.movie_id}, ${item.city_id})">
                                Book Show
                            </button>`;
                    });
                    $('#cinema_id').html(content);
                }
            });
            }

            // function to triger seat boking window
            bookMovie = function (cinemaId, movieId, cityId){
                console.log('in book movie console.');
                $.ajax({
                type:'GET',
                url:"{{ url('/select_seat') }}",
                data:
                    {
                        'cinemaId' : cinemaId,
                        'movieId' : movieId,
                        'cityId' : cityId
                    },
                success:function(data){
                    $('#available_seat_count').html(data);
                    $('#available_seat_count').show();
                    console.log('successfully booked movie');
                }
            });
            }

            // Onchange event handler for city
            $(document).on('change', '#city_id', function(){
            let cityId = $(this).val();
            $.ajax({
                type:'GET',
                url:"{{ url('/get_movie') }}",
                data:{'cityId':cityId},
                success:function(data){
                $('#cinema_id').hide();
                $('#available_seat_count').hide();
                    let options = '';
                    $.each(data, function(i, item) {
                            options += `<div value=${item.id} class="input"> ${item.movie_name}
                            </br>
                            <button type="button" class="btn btn-sm btn-light mt-2 mb-2" onClick="checkMovies(${item.id}, ${cityId})">Check Show</button>     
                            </div>`;
                    });
                    $('#movie_id').empty().html(options);
                }
            });
        });
        
    // Script for validation
    validate();
    $('#username').change(validate);
    });

    function validate(){
        if ($('#username').val().length   >   0  ) {
            $("input[type=submit]").prop("disabled", false);
        }
        else {
            $("input[type=submit]").prop("disabled", true);
        }
    }
    </script>
</html>
