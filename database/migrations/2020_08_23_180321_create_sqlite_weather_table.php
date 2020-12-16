<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSqliteWeatherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(env('DB_CONNECTION', 'mysql'))->create('weather', function (Blueprint $table) {
            $table->integer('id')->unique();
            $table->text('city');
            $table->longtext('forecast');
            $table->float('celsius');
            $table->float('fahrenheit');
            $table->float('apparent_celsius');
            $table->float('apparent_fahrenheit');
            $table->integer('wind_speed_bft');
            $table->float('wind_speed_kmh');
            $table->float('wind_speed_kn');
            $table->text('wind_direction');
            $table->integer('humidity');
            $table->integer('dew_point');
            $table->float('air_pressure');
            $table->integer('sight');
            $table->integer('sunrise');
            $table->integer('sunset');
            $table->text('icon_today');
            $table->text('icon_tomorrow');
            $table->text('icon_overmorrow');
            $table->integer('alarm');
            $table->text('alarm_text');
            $table->timestamps();
        });

        DB::connection(env('DB_CONNECTION', 'mysql'))->table('weather')->insert([
            'id' => 1,
            'city' => '',
            'forecast' => '',
            'celsius' => 0,
            'fahrenheit' => 0,
            'apparent_celsius' => 0,
            'apparent_fahrenheit' => 0,
            'wind_speed_bft' => 0,
            'wind_speed_kmh' => 0,
            'wind_speed_kn' => 0,
            'wind_direction' => '',
            'humidity' => 0,
            'dew_point' => 0,
            'air_pressure' => 0,
            'sight' => 0,
            'sunrise' => 0,
            'sunset' => 0,
            'icon_today' => '',
            'icon_tomorrow' => '',
            'icon_overmorrow' => '',
            'alarm' => 0,
            'alarm_text' => '',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('weather');
    }
}
