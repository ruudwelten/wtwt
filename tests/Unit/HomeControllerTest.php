<?php

namespace Tests\Unit;

use Tests\Unit\TestCase;
use App\Http\Controllers\HomeController;

class HomeControllerTest extends TestCase
{
    public function testGetTempColorReturnsEmptyString()
    {
        $controller = new HomeController();
        $getTempColor = $this->getPrivateMethod($controller, 'getTempColor');

        $this->assertEquals($getTempColor->invokeArgs($controller, [5]),   '');
        $this->assertEquals($getTempColor->invokeArgs($controller, ['6']), '');
        $this->assertEquals($getTempColor->invokeArgs($controller, ['a']), '');
    }

    public function testGetTempColorReturnsCorrectColor()
    {
        $controller = new HomeController();
        $getTempColor = $this->getPrivateMethod($controller, 'getTempColor');

        $this->assertEquals($getTempColor->invokeArgs($controller, [1.9]),     'blue');
        $this->assertEquals($getTempColor->invokeArgs($controller, [32]),      'red');
        $this->assertEquals($getTempColor->invokeArgs($controller, ['32']),    'red');
        $this->assertEquals($getTempColor->invokeArgs($controller, [28.65]),   'orange');
        $this->assertEquals($getTempColor->invokeArgs($controller, ['28.65']), 'orange');
        $this->assertEquals($getTempColor->invokeArgs($controller, [23.0]),    'yellow');
    }

    public function testGetIconReturnsFalseIfUnkownInput()
    {
        $controller = new HomeController();
        $getIcon = $this->getPrivateMethod($controller, 'getIcon');

        $this->assertFalse($getIcon->invokeArgs($controller, ['unknown']));
    }

    public function testGetIconReturnsCorrectUrl()
    {
        $controller = new HomeController();
        $getIcon = $this->getPrivateMethod($controller, 'getIcon');

        $this->assertEquals(
            $getIcon->invokeArgs($controller, ['bewolkt']),
            'https://raw.githubusercontent.com/jackd248/weather-iconic/master/sources/SVG/cloud.svg'
        );
        $this->assertEquals(
            $getIcon->invokeArgs($controller, ['sneeuw']),
            'https://raw.githubusercontent.com/jackd248/weather-iconic/master/sources/SVG/snowflake.svg'
        );
    }
}
