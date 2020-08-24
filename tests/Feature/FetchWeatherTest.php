<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

class FetchWeatherTest extends TestCase
{
    use RefreshDatabase;

    public function testFetchOnce()
    {
        $this->assertDatabaseCount('weather', 1, env('DB_CONNECTION', 'sqlite_test'));
        $this->assertDatabaseCount('weather', 0, env('DB_SECONDARY_CONNECTION', 'sqlite_test2'));

        Http::fake(['weerlive.nl/*' => $this->createFakeResponse()]);

        $this->artisan('weather:fetch', ['place' => 'Den Bosch']);

        $this->assertDatabaseCount('weather', 1, env('DB_CONNECTION', 'sqlite_test'));
        $this->assertDatabaseCount('weather', 1, env('DB_SECONDARY_CONNECTION', 'sqlite_test2'));
        $this->assertDatabaseHas('weather', $this->createFakeDataArray(), env('DB_CONNECTION', 'sqlite_test'));
        $this->assertDatabaseHas('weather', $this->createFakeDataArray(), env('DB_SECONDARY_CONNECTION', 'sqlite_test2'));
    }

    public function testFetchTwice()
    {
        $this->assertDatabaseCount('weather', 1, env('DB_CONNECTION', 'sqlite_test'));
        $this->assertDatabaseCount('weather', 0, env('DB_SECONDARY_CONNECTION', 'sqlite_test2'));

        Http::fake([
            'weerlive.nl/*' => Http::sequence()
                ->push(['liveweer' => [0 => $this->createFakeDataArray(0, true)]])
                ->push(['liveweer' => [0 => $this->createFakeDataArray(5, true)]])
        ]);

        $this->artisan('weather:fetch', ['place' => 'Den Bosch']);
        $this->artisan('weather:fetch', ['place' => 'Den Bosch']);

        $this->assertDatabaseCount('weather', 1, env('DB_CONNECTION', 'sqlite_test'));
        $this->assertDatabaseCount('weather', 2, env('DB_SECONDARY_CONNECTION', 'sqlite_test2'));

        // Default connection only has one record, which is updated every time.
        $this->assertDatabaseMissing('weather', $this->createFakeDataArray(), env('DB_CONNECTION', 'sqlite_test'));
        $this->assertDatabaseHas('weather', $this->createFakeDataArray(5), env('DB_CONNECTION', 'sqlite_test'));

        $this->assertDatabaseHas('weather', $this->createFakeDataArray(), env('DB_SECONDARY_CONNECTION', 'sqlite_test2'));
        $this->assertDatabaseHas('weather', $this->createFakeDataArray(5), env('DB_SECONDARY_CONNECTION', 'sqlite_test2'));
    }

    public function testFetchThrice()
    {
        $this->assertDatabaseCount('weather', 1, env('DB_CONNECTION', 'sqlite_test'));
        $this->assertDatabaseCount('weather', 0, env('DB_SECONDARY_CONNECTION', 'sqlite_test2'));

        Http::fake([
            'weerlive.nl/*' => Http::sequence()
                ->push(['liveweer' => [0 => $this->createFakeDataArray(0, true)]])
                ->push(['liveweer' => [0 => $this->createFakeDataArray(7, true)]])
                ->push(['liveweer' => [0 => $this->createFakeDataArray(11, true)]])
        ]);

        $this->artisan('weather:fetch', ['place' => 'Den Bosch']);
        $this->artisan('weather:fetch', ['place' => 'Den Bosch']);
        $this->artisan('weather:fetch', ['place' => 'Den Bosch']);

        $this->assertDatabaseCount('weather', 1, env('DB_CONNECTION', 'sqlite_test'));
        $this->assertDatabaseCount('weather', 3, env('DB_SECONDARY_CONNECTION', 'sqlite_test2'));

        // Default connection only has one record, which is updated every time.
        $this->assertDatabaseMissing('weather', $this->createFakeDataArray(0), env('DB_CONNECTION', 'sqlite_test'));
        $this->assertDatabaseMissing('weather', $this->createFakeDataArray(7), env('DB_CONNECTION', 'sqlite_test'));
        $this->assertDatabaseHas('weather', $this->createFakeDataArray(11), env('DB_CONNECTION', 'sqlite_test'));

        $this->assertDatabaseHas('weather', $this->createFakeDataArray(0), env('DB_SECONDARY_CONNECTION', 'sqlite_test2'));
        $this->assertDatabaseHas('weather', $this->createFakeDataArray(7), env('DB_SECONDARY_CONNECTION', 'sqlite_test2'));
        $this->assertDatabaseHas('weather', $this->createFakeDataArray(11), env('DB_SECONDARY_CONNECTION', 'sqlite_test2'));
    }

    private function createFakeResponse($i = 0)
    {
        return Http::response(['liveweer' => [0 => $this->createFakeDataArray($i, true)]], 200, ['Headers']);
    }

    private function createFakeDataArray($i = 0, $api = false)
    {
        $windDirection = ['Noord', 'NNO', 'NO', 'ONO', 'Oost', 'OZO', 'ZO',
                           'ZZO', 'Zuid', 'ZZW', 'ZW', 'WZW', 'West', 'WNW',
                           'NW', 'NNW'];
        $forecast = ['Bewolkt', 'Droog, vanavond onweer', 'Wisselend buien',
                     'Hagel de hele dag', 'Wisselvallig bewolkt met regen',
                     'Paar wolkjes', 'Koude nacht', 'Mistbanken, let op',
                     'Vannacht mistig', 'Regenachtig', 'Natte sneeuw',
                     'Geen vallende sterren vannacht', 'Zonnig. Morgen bewolkt',
                     'Grijs en grauw'];
        $icons = ['bewolkt', 'bliksem', 'buien', 'hagel', 'halfbewolkt_regen',
                  'halfbewolkt', 'helderenacht', 'mist', 'nachtmist', 'regen',
                  'sneeuw', 'wolkennacht', 'zonnig', 'zwaarbewolkt',
                  'bewolkt', 'bliksem'];
        $sunrise = ['05:11', '05:35', '05:59', '06:09', '06:35', '06:47',
                    '07:00', '07:31', '07:40', '07:58', '08:03', '08:11',
                    '08:13', '08:25', '08:33', '08:39', '08:44', '09:00'];
        $sunset = ['23:05', '22:59', '22:45', '22:29', '22:11', '21:59',
                   '21:47', '21:31', '21:23', '21:05', '20:40', '20:21',
                   '20:00', '19:29', '19:01', '18:38', '18:14', '17:46'];
        return [
            ($api ? 'verw'     : 'forecast') => $forecast[$i] . ($api ? '':'.'),
            ($api ? 'temp'     : 'celsius') => 10.4 + $i,
            ($api ? 'gtemp'    : 'apparent_celsius') => 5.1 + $i / 2,
            ($api ? 'winds'    : 'wind_speed_bft') => 3 + $i,
            ($api ? 'windkmh'  : 'wind_speed_kmh') => 10 + $i * 2,
            ($api ? 'windk'    : 'wind_speed_kn') => 6 + $i,
            ($api ? 'windr'    : 'wind_direction') => $windDirection[$i],
            ($api ? 'lv'       : 'humidity') => 30 + $i * 3,
            ($api ? 'dauwp'    : 'dew_point') => 11 + $i * 2,
            ($api ? 'luchtd'   : 'air_pressure') => 935.4 + $i * 11,
            ($api ? 'zicht'    : 'sight') => 5 * $i,
            ($api ? 'sup'      : 'sunrise') => ($api ? $sunrise[$i] : strtotime($sunrise[$i])),
            ($api ? 'sunder'   : 'sunset') => ($api ? $sunset[$i] : strtotime($sunset[$i])),
            ($api ? 'd0weer'   : 'icon_today') => $icons[$i],
            ($api ? 'd1weer'   : 'icon_tomorrow') => $icons[$i + 1],
            ($api ? 'd2weer'   : 'icon_overmorrow') => $icons[$i + 2],
            ($api ? 'alarm'    : 'alarm') => 0,
            ($api ? 'alarmtxt' : 'alarm_text') => '',
        ];
    }
}
