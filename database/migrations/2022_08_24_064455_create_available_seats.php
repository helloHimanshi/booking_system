<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('available_seats', function (Blueprint $table) {
            $table->id();
            $table->Integer('seat_id');
            $table->boolean('is_booked')->default(0);
            $table->String('user_name');
            $table->Integer('movie_id');
            $table->Integer('city_id');
            $table->Integer('cinema_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('available_seats');
    }
};
