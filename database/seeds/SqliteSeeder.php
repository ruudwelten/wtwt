<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class SqliteSeeder extends Seeder
{
    /**
     * Seed the MySQL database.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('nl_NL');

        $windDirections = ['Noord', 'NNO', 'NO', 'ONO', 'Oost', 'OZO', 'ZO',
                           'ZZO', 'Zuid', 'ZZW', 'ZW', 'WZW', 'West', 'WNW',
                           'NW', 'NNW'];
        $icons = ['bewolkt', 'bliksem', 'buien', 'hagel', 'halfbewolkt_regen',
                  'halfbewolkt', 'helderenacht', 'mist', 'nachtmist', 'regen',
                  'sneeuw', 'wolkennacht', 'zonnig', 'zwaarbewolkt'];

        $celsius = rand(-100, 350) / 10;
        $celsius_apparent = rand($celsius * 10 - 50, $celsius * 10) / 10;
        $fahrenheit = $celsius / 5 * 9 + 32;
        $fahrenheit_apparent = $celsius_apparent / 5 * 9 + 32;
        DB::connection(env('DB_CONNECTION', 'sqlite_test'))
            ->table('weather')
            ->where('id', 1)
            ->update([
                'id' => 1,
                'city' => $faker->city,
                'forecast' => $faker->realText(50, 1),
                'celsius' => $celsius,
                'fahrenheit' => $fahrenheit,
                'apparent_celsius' => $celsius_apparent,
                'apparent_fahrenheit' => $fahrenheit_apparent,
                'wind_speed_bft' => rand(0, 10),
                'wind_speed_kmh' => rand(0, 300) / 10,
                'wind_speed_kn' => rand(0, 350) / 10,
                'wind_direction' => $windDirections[array_rand($windDirections)],
                'humidity' => rand(30, 60),
                'dew_point' => rand(5, 30),
                'air_pressure' => rand(9000, 11000) / 10,
                'sight' => rand(0, 100),
                'sunrise' => mktime(rand(5, 8), rand(0, 59), rand(0, 59), date('n'), date('j'), date('Y')),
                'sunset' => mktime(rand(17, 22), rand(0, 59), rand(0, 59), date('n'), date('j'), date('Y')),
                'icon_today' => $icons[array_rand($icons)],
                'icon_tomorrow' => $icons[array_rand($icons)],
                'icon_overmorrow' => $icons[array_rand($icons)],
                'alarm' => 0,
                'alarm_text' => '',
                'created_at' => date('Y-m-d H:i:s', strtotime("-" . rand(0, 86400) . " minutes")),
                'updated_at' => date('Y-m-d H:i:s', strtotime("-" . rand(0, 86400) . " minutes")),
            ]);
    }
}
