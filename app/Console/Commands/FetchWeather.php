<?php

namespace App\Console\Commands;

use App\Weather;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

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
        // Get weather data from weerlive.nl API, set WEATHER_API_KEY in .env
        $response = Http::retry(3, 10000)->get(
            'http://weerlive.nl/api/json-data-10min.php?key=' .
            env('WEATHER_API_KEY') . '&locatie=' . $this->argument('place')
        );

        if ($response->successful()) {
            $weatherData = $this->wrangleApiResponse($response);

            Weather::saveToBothDatabases($weatherData);
        }
    }

    private function celsiusToFahrenheit($celsius)
    {
        return ($celsius * 9 / 5) + 32;
    }

    private function wrangleApiResponse($data)
    {
        $data = $data['liveweer'][0];
        $weatherData = [
            'city'                => $this->argument('place'),
            'celsius'             => $data['temp'],
            'fahrenheit'          => $this->celsiusToFahrenheit($data['temp']),
            'apparent_celsius'    => $data['gtemp'],
            'apparent_fahrenheit' => $this->celsiusToFahrenheit($data['gtemp']),
            'wind_speed_bft'      => $data['winds'],
            'wind_speed_kmh'      => $data['windkmh'],
            'wind_speed_kn'       => $data['windk'],
            'wind_direction'      => $data['windr'],
            'humidity'            => $data['lv'],
            'dew_point'           => $data['dauwp'],
            'air_pressure'        => $data['luchtd'],
            'sight'               => $data['zicht'],
            'sunrise'             => strtotime($data['sup']),
            'sunset'              => strtotime($data['sunder']),
            'icon_today'          => $data['d0weer'],
            'icon_tomorrow'       => $data['d1weer'],
            'icon_overmorrow'     => $data['d2weer'],
            'alarm'               => $data['alarm'],
            'alarm_text'          => $data['alarmtxt'] ?? '',  // alarmtxt is not included when alarm == 0
        ];

        // Add closing period to forecast
        $weatherData['forecast'] = $data['verw'] . '.';
        if ($weatherData['forecast'] == '_.') {
            $weatherData['forecast'] = '';
        }

        return $weatherData;
    }
}
