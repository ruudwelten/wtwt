<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Weather extends Model
{
    protected $table = 'weather';
    protected $fillable = ['city', 'forecast', 'celsius', 'fahrenheit',
                           'apparent_celsius', 'apparent_fahrenheit',
                           'wind_speed_bft', 'wind_speed_kmh', 'wind_speed_kn',
                           'wind_direction', 'humidity', 'dew_point',
                           'air_pressure', 'sight', 'sunrise', 'sunset',
                           'icon_today', 'icon_tomorrow', 'icon_overmorrow',
                           'alarm', 'alarm_text', 'updated_at'];

    public static function getTemperaturesLastDay()
    {
        return self::on(env('DB_SECONDARY_CONNECTION', 'mysql'))
            ->select('celsius AS y', 'created_at AS t')
            ->where('created_at', '>', date('Y-m-d H:i:s', strtotime("-1 day")))
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public static function getHighLowTemperatures($grouping, $start)
    {
        return self::on(env('DB_SECONDARY_CONNECTION', 'mysql'))
            ->select(
                DB::raw('MAX(celsius) as high'),
                DB::raw('MIN(celsius) as low'),
                DB::raw($grouping . ' as `grouping`')
            )
            ->where('created_at', '>', date('Y-m-d H:i:s', strtotime($start)))
            ->orderBy(DB::raw($grouping), 'asc')
            ->groupBy(DB::raw($grouping))
            ->get();
    }

    public static function saveToBothDatabases($weatherData)
    {
        // Update latest data in SQLite database
        $instance = (new self)->setConnection(env('DB_CONNECTION', 'sqlite'));
        $status = $instance->find(1)
            ->fill($weatherData)
            ->save();

        if (!$status) {
            return $status;
        }

        // Store historic data in MySQL database
        return self::on(env('DB_SECONDARY_CONNECTION', 'mysql'))
            ->create($weatherData);
    }
}
