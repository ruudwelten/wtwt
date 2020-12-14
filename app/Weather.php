<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Weather extends Model
{
    protected $table = 'weather';

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
}
