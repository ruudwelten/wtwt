<?php

namespace App\Http\Controllers;

use App\Weather;

class HomeController extends Controller
{
    /**
     * Show the latest temperature data.
     *
     * @return View
     */
    public function index()
    {
        $weather = Weather::on('sqlite')->latest()->first();

        $data = $weather->toArray();

        $data['celsius'] = round($weather->celsius, 1);
        $data['fahrenheit'] = round($weather->fahrenheit, 1);
        $data['tempColor'] = $this->getTempColor($weather->celsius);
        $data['icon_today'] = $this->getIcon($weather->icon_today);
        $data['icon_tomorrow'] = $this->getIcon($weather->icon_tomorrow);
        $data['icon_overmorrow'] = $this->getIcon($weather->icon_overmorrow);

        return view('home', $data);
    }

    private function getTempColor($temperatureInCelsius)
    {
        if (!is_numeric($temperatureInCelsius)) {
            return '';
        }

        if ($temperatureInCelsius < 2) {
            return 'blue';
        } elseif ($temperatureInCelsius > 30) {
            return 'red';
        } elseif ($temperatureInCelsius > 26) {
            return 'orange';
        } elseif ($temperatureInCelsius > 22) {
            return 'yellow';
        }
        return '';
    }

    private function getIcon($iconInDutch)
    {
        $iconsTranslation = [
            'bewolkt'           => 'cloud',
            'bliksem'           => 'lightning',
            'buien'             => 'cloud-rain-single',
            'hagel'             => 'hail',
            'halfbewolkt_regen' => 'sun-cloud-rain',
            'halfbewolkt'       => 'sun-cloud',
            'helderenacht'      => 'moon',
            'mist'              => 'fog',
            'nachtmist'         => 'moon-fog',
            'regen'             => 'cloud-rain',
            'sneeuw'            => 'snowflake',
            'wolkennacht'       => 'moon-cloud',
            'zonnig'            => 'sun',
            'zwaarbewolkt'      => 'cloud',
        ];

        $url = 'https://raw.githubusercontent.com/jackd248/weather-iconic/master/sources/SVG/{icon}.svg';

        if (isset($iconsTranslation[$iconInDutch])) {
            return str_replace('{icon}', $iconsTranslation[$iconInDutch], $url);
        }

        return false;
    }
}
