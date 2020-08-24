<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApiNotification;
use App\Mail\TemperatureNotification;

class NotifyEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email notifications';

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
        $this->sendApiNotification();
        $this->sendTemperatureNotification();
    }

    /**
     * Send notification if API has been down for 2 hours
     */
    private function sendApiNotification()
    {
        $latest = DB::connection(env('DB_SECONDARY_CONNECTION', 'mysql'))
            ->table('weather')
            ->select('created_at')
            ->latest()
            ->first();

        if (!$latest) {
            return;
        }
        if (
            strtotime($latest->created_at) >= strtotime("-105 minutes") &&
            strtotime($latest->created_at) <= strtotime("-135 minutes")
        ) {
            Mail::to(env('NOTIFICATION_EMAIL'))
                ->send(new ApiNotification($latest->created_at));
        }
    }

    /**
     * Send notification if temperature rises above 30 degrees celsius!
     */
    private function sendTemperatureNotification()
    {
        $latest = DB::connection(env('DB_SECONDARY_CONNECTION', 'mysql'))
            ->table('weather')
            ->select('celsius', 'fahrenheit')
            ->latest()
            ->first();

        if (!$latest) {
            return;
        }
        if ($latest->celsius < 30) {
            return;
        }

        $heat = DB::connection(env('DB_SECONDARY_CONNECTION', 'mysql'))
            ->table('weather')
            ->select('created_at')
            ->where('celsius', '>=', 30)
            ->where('created_at', '>=', date('Y-m-d H:i:s', strtotime('-12 hours')))
            ->latest()
            ->get();

        if (!$heat) {
            return;
        }

        // Only send mail if it is the first record above 30 degrees celsius
        if ($heat->count() == 1) {
            Mail::to(env('NOTIFICATION_EMAIL'))
                ->send(new TemperatureNotification($latest->celsius, $latest->fahrenheit));
        }
    }
}
