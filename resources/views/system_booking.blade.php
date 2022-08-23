<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>System Booking</title>
        <!-- CSS only -->
        <link href="{{ URL::asset('css/system_booking.css') }}" rel="stylesheet" type="text/css">
        <!-- Bootstrap CDN -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    </head>
    <body class="antialiased">
    <div class="form">
      <div class="title">Book Your Tickets</div>
      <div class="input-container ic1">
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
      <div class="input-container ic2">
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
    </div>
    </body>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script>
        let checkMovies;
        $(document).ready(function() {
            checkMovies =  function (movieId, cityId){
            console.log(movieId, cityId);
            $.ajax({
                type:'GET',
                url:"{{ url('/get_cinema') }}",
                data:{
                    'movieId': movieId,
                    'cityId': cityId
                },
                success:function(data){
                    console.log('success');
                }
            });
            }
            $(document).on('change', '#city_id', function(){
            let cityId = $(this).val();
            $.ajax({
                type:'GET',
                url:"{{ url('/get_movie') }}",
                data:{'cityId':cityId},
                success:function(data){
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
        //     $(document).on('change', '#movie_id', function(){
        //     let movieId = $(this).val();
        //     console.log(movieId);
        //     $.ajax({
        //         type:'GET',
        //         url:"{{ url('/get_cinema') }}",
        //         data:{'movieId': movieId},
        //         success:function(data){
        //             let options = '';
        //             let showTime = '';
        //             $.each(data, function(i, item) {
        //                 console.log(item.id);
        //                 console.log(item.Cinema_name);
        //                 // loop through the json and create an option for each result
        //                 options += `<option value=${item.id}> ${item.Cinema_name} </option>`;
        //                 showTime += `<input value=${item.show_time}>`
        //             });
        //             $('#cinema_id').empty().html(options);
        //             $('#cinema_show_time').html(showTime);
        //         }
        //     });
        // });
    });
    </script>
</html>
