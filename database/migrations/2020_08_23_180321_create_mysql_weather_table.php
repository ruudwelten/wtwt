<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMysqlWeatherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $connection = env('DB_SECONDARY_CONNECTION', 'mysql');
        if ($connection == 'mysql') {
            Schema::connection('mysql')->create('weather', function (Blueprint $table) {
                $table->increments('id');
                $table->string('city', 100);
                $table->longtext('forecast');
                $table->float('celsius');
                $table->float('fahrenheit');
                $table->float('apparent_celsius');
                $table->float('apparent_fahrenheit');
                $table->integer('wind_speed_bft');
                $table->float('wind_speed_kmh');
                $table->float('wind_speed_kn');
                $table->string('wind_direction', 5);
                $table->integer('humidity');
                $table->integer('dew_point');
                $table->float('air_pressure');
                $table->integer('sight');
                $table->integer('sunrise');
                $table->integer('sunset');
                $table->string('icon_today', 30);
                $table->string('icon_tomorrow', 30);
                $table->string('icon_overmorrow', 30);
                $table->integer('alarm');
                $table->text('alarm_text');
                $table->timestamps();
            });
        } else {
            Schema::connection('sqlite_test2')->create('weather', function (Blueprint $table) {
                $table->increments('id');
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
        }
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
