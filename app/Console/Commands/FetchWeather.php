<?php

namespace App\Console\Commands;

use App\Weather;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class FetchWeather extends Command
{
    private $apiKey;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weather:fetch {place}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and save live weather data from weerlive.nl API.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Get weather data from weelive.nl API, set WEATHER_API_KEY in .env
        $response = Http::retry(3, 10000)->get(
            'http://weerlive.nl/api/json-data-10min.php?key=' .
            env('WEATHER_API_KEY') . '&locatie=' . $this->argument('place')
        );
        $weather = $response['liveweer'][0];

        if ($response->successful()) {
            $time = date('Y-m-d H:i:s');

            // alarmtxt is not included when alarm == 0
            if ($weather['alarm'] == 0) {
                $weather['alarmtxt'] = '';
            }

            // Add closing period to forecast
            if ($weather['verw'] == '_') {
                $weather['verw'] = '';
            } else {
                $weather['verw'] .= '.';
            }

            // Update latest data in SQLite database
            DB::connection(env('DB_CONNECTION', 'sqlite'))->table('weather')->where('id', 1)->update([
                'city' => $this->argument('place'),
                'forecast' => $weather['verw'],
                'celsius' => $weather['temp'],
                'fahrenheit' => $this->celsiusToFahrenheit($weather['temp']),
                'apparent_celsius' => $weather['gtemp'],
                'apparent_fahrenheit' => $this->celsiusToFahrenheit($weather['gtemp']),
                'wind_speed_bft' => $weather['winds'],
                'wind_speed_kmh' => $weather['windkmh'],
                'wind_speed_kn' => $weather['windk'],
                'wind_direction' => $weather['windr'],
                'humidity' => $weather['lv'],
                'dew_point' => $weather['dauwp'],
                'air_pressure' => $weather['luchtd'],
                'sight' => $weather['zicht'],
                'sunrise' => strtotime($weather['sup']),
                'sunset' => strtotime($weather['sunder']),
                'icon_today' => $weather['d0weer'],
                'icon_tomorrow' => $weather['d1weer'],
                'icon_overmorrow' => $weather['d2weer'],
                'alarm' => $weather['alarm'],
                'alarm_text' => $weather['alarmtxt'],
                'updated_at' => $time,
            ]);

            // Store historic data in MySQL database
            DB::connection(env('DB_SECONDARY_CONNECTION', 'mysql'))
                ->table('weather')
                ->insert([
                    'city' => $this->argument('place'),
                    'forecast' => $weather['verw'],
                    'celsius' => $weather['temp'],
                    'fahrenheit' => $this->celsiusToFahrenheit($weather['temp']),
                    'apparent_celsius' => $weather['gtemp'],
                    'apparent_fahrenheit' => $this->celsiusToFahrenheit($weather['gtemp']),
                    'wind_speed_bft' => $weather['winds'],
                    'wind_speed_kmh' => $weather['windkmh'],
                    'wind_speed_kn' => $weather['windk'],
                    'wind_direction' => $weather['windr'],
                    'humidity' => $weather['lv'],
                    'dew_point' => $weather['dauwp'],
                    'air_pressure' => $weather['luchtd'],
                    'sight' => $weather['zicht'],
                    'sunrise' => strtotime($weather['sup']),
                    'sunset' => strtotime($weather['sunder']),
                    'icon_today' => $weather['d0weer'],
                    'icon_tomorrow' => $weather['d1weer'],
                    'icon_overmorrow' => $weather['d2weer'],
                    'alarm' => $weather['alarm'],
                    'alarm_text' => $weather['alarmtxt'],
                    'created_at' => $time,
                ]);
        }
    }

    private function celsiusToFahrenheit($celsius)
    {
        return ($celsius * 9 / 5) + 32;
    }
}
