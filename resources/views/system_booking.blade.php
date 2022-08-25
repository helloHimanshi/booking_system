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
        <!-- Laravel validation error message -->
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
      <div class="mt-4 mb-4 title">Book Your Tickets</div>
      <div class="mt-4 mb-4">
        <label class="subtitle">Select City</label>
            <select id="city_id" class="input" style="height:50px;">
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
        <label id="movie_tag" class="subtitle">Select Movie</label>
        <div id="movie_id" class="d-flex justify-content-around">
            @foreach($movies as $movie)
                <div value="{{$movie->id}}" class="input" onClick="confirm('Select City to book the show.')">
                    {{$movie->movie_name}}
                </div>
            @endforeach
        </div>
        </div>
        <div class="mt-2 mb-4">
            <label id="cinema_tag" class="subtitle" style="display:none;">Select Cinema</label>
            <div id="cinema_id" value="" class="input" style="display:none;">
            </div>
        </div>
        <div class="mt-2">
            <div id="available_seat_count" class="input" style="display:none;"> 
        </div>
    </div>
    </body>
    <!-- Script to run ajax -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <!-- Script to call jquery validate function -->
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <script>
        let checkMovies;
        let bookMovie;
        $(document).ready(function() {
            checkMovies =  function (movieId, cityId){
            console.log(movieId, cityId);
            $('#cinema_id').hide();
            $('#cinema_tag').hide();
            $('#available_seat_count').hide();
            $.ajax({
                type:'GET',
                url:"{{ url('/get_cinema') }}",
                data:{
                    'movieId': movieId,
                    'cityId': cityId
                },
                success:function(data){
                    
                $('#cinema_id').show();
                $('#cinema_tag').show();
                    let content = '';
                    if(data.length == 0){
                        content += `No Show Available`;
                    } else {
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
                                </button> </br>`;
                        });
                    }
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
                $('#cinema_tag').hide();
                $('#available_seat_count').hide();
                $('#movie_tag').hide();
                    let options = '';
                    if(data.length == 0){
                        options += `<div class="input">No Movie Available</div>`
                    } else {
                        $.each(data, function(i, item) {
                                options += `<div value=${item.id} class="input"> ${item.movie_name}
                                </br>
                                <button type="button" class="btn btn-sm btn-light mt-2 mb-2" onClick="checkMovies(${item.id}, ${cityId})">Check Show</button>     
                                </div>`;
                        });
                    }
                    $('#movie_tag').show();
                    $('#movie_id').empty().html(options);
                }
            });
        });
    });
    </script>
</html>
