<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApiNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $datetime;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($datetime)
    {
        $this->datetime = $datetime;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.notify_api_down')
                    ->text('emails.notify_api_down_plain');
    }
}
