        <form method="POST" action="{{url('/select_seat')}}">
            {{ csrf_field() }}
            <input type="hidden" value="{{$city_id}}" name="city">
            <input type="hidden" value="{{$cinema_id}}" name="cinema">
            <input type="hidden" value="{{$movie_id}}" name="movie">
            <div class="exit exit--front fuselage">
            </div>
            <ol class="cabin fuselage">
                <li class="row">
                    @foreach($seats as $seat)
                    <ol class="seats">
                    @foreach($seat as $numbered_seat)
                        <li class="seat">
                        <input type="checkbox" 
                        id="{{$numbered_seat->seat_name}}" 
                        value="{{$numbered_seat->id}}"  
                        name="checkbox[{{$numbered_seat->id}}]"
                        />
                        <label for="{{$numbered_seat->seat_name}}">{{$numbered_seat->seat_name}}</label>
                        </li>
                        @endforeach
                    </ol>
                    @endforeach
                </li>
            </ol>
            <div class="exit exit--back fuselage">
            </div>
            <input id="username" name="username" type="text" class="input mt-4 mb-2" 
            placeholder="Please Enter Your Name"
            style="background-color: #898eb9; height: 50px;">
            <button type="submit" class="btn btn-light mt-2 mb-2">Confirm Seats</botton>
        </form>
