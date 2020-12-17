<?php

namespace App\Http\Controllers;

use App\Weather;

class HistoryController extends Controller
{
    /**
     * Show the historic temperature data.
     *
     * @return View
     */
    public function index()
    {
        return view('history', [
            'dayDataset' => $this->constructDayDataset(),
            'weekDataset' => $this->constructWeekDataset(),
            'monthDataset' => $this->constructMonthDataset(),
            'yearDataset' => $this->constructYearDataset()
        ]);
    }

    private function constructDayDataset()
    {
        return Weather::getTemperaturesLastDay();
    }

    private function constructWeekDataset()
    {
        return $this->constructHighLowDataset(
            'DATE(created_at)',
            '-1 week',
        );
    }

    private function constructMonthDataset()
    {
        return $this->constructHighLowDataset(
            'DATE(created_at)',
            '-1 month',
        );
    }

    private function constructYearDataset()
    {
        // MySQL and SQLite support different date functions.
        // For testing SQLite is used.
        if (env('DB_SECONDARY_CONNECTION', 'mysql') == 'mysql') {
            $dbSpecificDate = 'DATE(SUBDATE(created_at, WEEKDAY(created_at)))';
        } else {
            $dbSpecificDate = 'date(created_at, \'weekday 0\', \'-6 days\')';
        }
        return $this->constructHighLowDataset(
            $dbSpecificDate,
            '-1 year',
        );
    }

    /**
     * Construct dataset for graphs with a high and low temperature
     * value per data point.
     */
    private function constructHighLowDataset($grouping, $start)
    {
        $data = Weather::getHighLowTemperatures($grouping, $start);
        $lowData = [];
        $highData = [];
        foreach ($data as $key => $value) {
            $lowData[$key] = [
                't' => $value->grouping,
                'y' => $value->low,
            ];
            $highData[$key] = [
                't' => $value->grouping,
                'y' => $value->high,
            ];
        }
        return ['high' => $highData, 'low' => $lowData];
    }
}
