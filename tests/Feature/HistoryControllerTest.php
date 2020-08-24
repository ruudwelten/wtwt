<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\HistoryController;

class HistoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testConstructDayDatasetEmpty()
    {
        $controller = new HistoryController();
        $constructDayDataset = $this->getPrivateMethod($controller, 'constructDayDataset');

        $expected = [];

        $this->assertEquals($constructDayDataset->invoke($controller), $expected);
    }

    public function testConstructHighLowDatasetEmpty()
    {
        $controller = new HistoryController();
        $constructHighLowDataset = $this->getPrivateMethod($controller, 'constructHighLowDataset');

        $expected = ['high' => [], 'low' => []];

        $this->assertEquals($constructHighLowDataset->invokeArgs(
            $controller,
            ['datetime(strftime(\'%s\', created_at) - strftime(\'%s\', created_at) % 3600, \'unixepoch\')', '-1 day']
        ), $expected);
    }

    public function testConstructWeekDatasetEmpty()
    {
        $controller = new HistoryController();
        $constructWeekDataset = $this->getPrivateMethod($controller, 'constructWeekDataset');

        $expected = ['high' => [], 'low' => []];

        $this->assertEquals($constructWeekDataset->invoke($controller), $expected);
    }

    public function testConstructMonthDatasetEmpty()
    {
        $controller = new HistoryController();
        $constructMonthDataset = $this->getPrivateMethod($controller, 'constructMonthDataset');

        $expected = ['high' => [], 'low' => []];

        $this->assertEquals($constructMonthDataset->invoke($controller), $expected);
    }

    public function testConstructYearDatasetEmpty()
    {
        $controller = new HistoryController();
        $constructYearDataset = $this->getPrivateMethod($controller, 'constructYearDataset');

        $expected = ['high' => [], 'low' => []];

        $this->assertEquals($constructYearDataset->invoke($controller), $expected);
    }

    public function testConstructDayDatasetCorrectTestData()
    {
        $this->seed();

        $controller = new HistoryController();
        $constructDayDataset = $this->getPrivateMethod($controller, 'constructDayDataset');

        $result = $constructDayDataset->invoke($controller);

        $this->assertEquals(count($result), 5);
        foreach ($result as $r) {
            $this->assertArrayHasKey('t', $r);
            $this->assertArrayHasKey('y', $r);
            $this->assertStringMatchesFormat('%d-%d-%d%c%d:%d:%d%S', $r['t']);
            $this->assertTrue(is_numeric($r['y']));
        }
    }

    public function testConstructHighLowDatasetCorrectTestData()
    {
        $this->seed();

        $controller = new HistoryController();
        $constructHighLowDataset = $this->getPrivateMethod($controller, 'constructHighLowDataset');

        $result = $constructHighLowDataset->invokeArgs(
            $controller,
            ['datetime(strftime(\'%s\', created_at) - strftime(\'%s\', created_at) % 3600, \'unixepoch\')', '-1 day']
        );

        $this->assertArrayHasKey('high', $result);
        $this->assertArrayHasKey('low', $result);
        foreach ($result as $set) {
            $this->assertTrue(count($set) == 4 || count($set) == 5);
            foreach ($set as $r) {
                $this->assertArrayHasKey('t', $r);
                $this->assertArrayHasKey('y', $r);
                $this->assertStringMatchesFormat('%d-%d-%d%c%d:%d:%d%S', $r['t']);
                $this->assertTrue(is_numeric($r['y']));
            }
        }
    }

    public function testConstructWeekDatasetCorrectTestData()
    {
        $this->seed();

        $controller = new HistoryController();
        $constructWeekDataset = $this->getPrivateMethod($controller, 'constructWeekDataset');

        $result = $constructWeekDataset->invoke($controller);

        $this->assertArrayHasKey('high', $result);
        $this->assertArrayHasKey('low', $result);
        foreach ($result as $set) {
            $this->assertEquals(count($set), 4);
            foreach ($set as $r) {
                $this->assertArrayHasKey('t', $r);
                $this->assertArrayHasKey('y', $r);
                $this->assertStringMatchesFormat('%d-%d-%d', $r['t']);
                $this->assertTrue(is_numeric($r['y']));
            }
        }
    }

    public function testConstructMonthDatasetCorrectTestData()
    {
        $this->seed();

        $controller = new HistoryController();
        $constructMonthDataset = $this->getPrivateMethod($controller, 'constructMonthDataset');

        $result = $constructMonthDataset->invoke($controller);

        $this->assertArrayHasKey('high', $result);
        $this->assertArrayHasKey('low', $result);
        foreach ($result as $set) {
            $this->assertTrue(count($set) == 7 || count($set) == 8);
            foreach ($set as $r) {
                $this->assertArrayHasKey('t', $r);
                $this->assertArrayHasKey('y', $r);
                $this->assertStringMatchesFormat('%d-%d-%d', $r['t']);
                $this->assertTrue(is_numeric($r['y']));
            }
        }
    }

    public function testConstructYearDatasetCorrectTestData()
    {
        $this->seed();

        $controller = new HistoryController();
        $constructYearDataset = $this->getPrivateMethod($controller, 'constructYearDataset');

        $result = $constructYearDataset->invoke($controller);

        $this->assertArrayHasKey('high', $result);
        $this->assertArrayHasKey('low', $result);
        foreach ($result as $set) {
            $this->assertTrue(count($set) == 9 || count($set) == 10 || count($set) == 11);
            foreach ($set as $r) {
                $this->assertArrayHasKey('t', $r);
                $this->assertArrayHasKey('y', $r);
                $this->assertStringMatchesFormat('%d-%d-%d', $r['t']);
                $this->assertTrue(is_numeric($r['y']));
            }
        }
    }
}
