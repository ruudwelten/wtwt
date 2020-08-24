<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class MysqlSeeder extends Seeder
{
    private $wind_directions = ['Noord', 'NNO', 'NO', 'ONO', 'Oost', 'OZO',
                                'ZO', 'ZZO', 'Zuid', 'ZZW', 'ZW', 'WZW', 'West',
                                'WNW', 'NW', 'NNW'];
    private $icons = ['bewolkt', 'bliksem', 'buien', 'hagel',
                      'halfbewolkt_regen', 'halfbewolkt', 'helderenacht',
                      'mist', 'nachtmist', 'regen', 'sneeuw', 'wolkennacht',
                      'zonnig', 'zwaarbewolkt'];
    private $faker;

    public function __construct()
    {
        gc_collect_cycles();
        $this->faker = Faker::create('nl_NL');
    }

    /**
     * Seed the MySQL database.
     *
     * @return void
     */
    public function run()
    {
        $dates = [
            // Five records in the last 24 hours
            strtotime('-1 hour'),
            strtotime('-87 minutes'),
            strtotime('-141 minutes'),
            strtotime('-907 minutes'),
            strtotime('-1312 minutes'),
            // Four more in the last week
            strtotime('-25 hours'),
            strtotime('-36 hours'),
            strtotime('-2 days'),
            strtotime('-4 days'),
            // Four more in the last month
            strtotime('-9 days'),
            strtotime('-13 days'),
            strtotime('-19 days'),
            strtotime('-27 days'),
            // Five more in the last year
            strtotime('-41 days'),
            strtotime('-152 days'),
            strtotime('-237 days'),
            strtotime('-290 days'),
            strtotime('-352 days'),
        ];
        foreach ($dates as $date) {
            $this->addRecord($date);
        }
    }

    private function addRecord($date = false)
    {
        if ($date === false) {
            $date = mktime(rand(5, 8), rand(0, 59), rand(0, 59), date('n'), date('j'), date('Y'));
        }
        $celsius = rand(-100, 350) / 10;
        $celsius_apparent = rand($celsius * 10 - 50, $celsius * 10) / 10;
        $fahrenheit = $celsius / 5 * 9 + 32;
        $fahrenheit_apparent = $celsius_apparent / 5 * 9 + 32;
        DB::connection(env('DB_SECONDARY_CONNECTION', 'sqlite_test2'))
            ->table('weather')
            ->insert([
                'city' => env('WEATHER_LOCATION', $this->faker->city),
                'forecast' => $this->faker->realText(50, 1),
                'celsius' => $celsius,
                'fahrenheit' => $fahrenheit,
                'apparent_celsius' => $celsius_apparent,
                'apparent_fahrenheit' => $fahrenheit_apparent,
                'wind_speed_bft' => rand(0, 10),
                'wind_speed_kmh' => rand(0, 300) / 10,
                'wind_speed_kn' => rand(0, 350) / 10,
                'wind_direction' => $this->wind_directions[array_rand($this->wind_directions)],
                'humidity' => rand(30, 60),
                'dew_point' => rand(5, 30),
                'air_pressure' => rand(9000, 11000) / 10,
                'sight' => rand(0, 100),
                'sunrise' => mktime(rand(5, 8), rand(0, 59), rand(0, 59), date('n'), date('j'), date('Y')),
                'sunset' => mktime(rand(17, 22), rand(0, 59), rand(0, 59), date('n'), date('j'), date('Y')),
                'icon_today' => $this->icons[array_rand($this->icons)],
                'icon_tomorrow' => $this->icons[array_rand($this->icons)],
                'icon_overmorrow' => $this->icons[array_rand($this->icons)],
                'alarm' => 0,
                'alarm_text' => '',
                'created_at' => date('Y-m-d H:i:s', $date),
            ]);
    }
}
