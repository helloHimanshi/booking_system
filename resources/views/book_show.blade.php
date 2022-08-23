<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="{{ URL::asset('css/system_booking.css') }}" rel="stylesheet" type="text/css">
        <title>Book Show</title>
        <!-- CSS only -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    </head>
    <body class="antialiased">
        <div class="form">
        <div class="input-container ic2">
        <label class="subtitle">Available Cinemas</label>
                @if($cinemas)
                    @foreach($cinemas as $cinema)
                        <div id="cinema_id" value="{{$cinema->id}}" class="input"> {{$cinema->Cinema_name}}
                            </br>
                            <span>{{date('d F Y, h:i:s a', strtotime($cinema->show_time))}}</span>
                            </br>
                            <button type="button" 
                            class="btn btn-sm btn-light mt-2 mb-2" 
                            onClick="bookMovie({{$cinema->id}}, {{$cinema->movie_id}}, {{$cinema->city_id}})">
                                Book Show
                            </button>
                        </div>
                    @endforeach
                @endif
        </div>
        </div>
        <script>
            function bookMovie(cinemaId, movieId, cityId){
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
                    console.log('successfully booked movie');
                }
            });
            }
        </script>
    </body>
</html>
