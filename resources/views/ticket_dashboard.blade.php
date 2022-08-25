<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Ticket Dashboard</title>
        <!-- CSS only -->
        <link href="{{ URL::asset('css/system_booking.css') }}" rel="stylesheet" type="text/css">
        <!-- Bootstrap CDN -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    </head>
    <body style="height:100vh;">
        <div class="form" style="height:auto;">
            <div class="mt-4 mb-4 title">Ticket Booked Successfully</div>
            <div class="mt-4 mb-4">
            <label class="subtitle">Seat Number : </label>
                @foreach($seats_selected as $seat_booked)
                    <span class="subtitle">{{$seat_booked}}<span>
                @endforeach
            </div>
            <div class="mt-4 mb-4">
            <label class="subtitle">Movie : </label>
            <span class="subtitle">{{$movie}}</span>
            </div>
            <div class="mt-4 mb-4">
            <label class="subtitle">Cinema : </label>
            <span class="subtitle">{{$cinema->Cinema_name}}</span>
            </div>
            <div class="mt-4 mb-4">
            <label class="subtitle">Show time : </label>
            <span class="subtitle">{{date('Y-m-d h:i a', strtotime($cinema->show_time))}}</span>
            </div>
            <div class="mt-4 mb-4">
            <label class="subtitle">Name : </label>
            <span class="subtitle">{{$username}}</span>
            </div>
            <div class="mt-4 mb-4">
                <a href="/system_booking">
                <button class="btn btn-light">Book Another Ticket</button>
                </a>
            </div>
        </div>
        <script type = "text/javascript" >  
            function preventBack() { window.history.forward(); }  
            setTimeout("preventBack()", 0);  
            window.onunload = function () { null };  
        </script>
    </body>
</html>
