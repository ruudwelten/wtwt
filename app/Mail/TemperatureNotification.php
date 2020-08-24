<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TemperatureNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $celsius;
    public $fahrenheit;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($celsius, $fahrenheit)
    {
        $this->celsius = $celsius;
        $this->fahrenheit = $fahrenheit;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.notify_temperature')
                    ->text('emails.notify_temperature_plain');
    }
}
