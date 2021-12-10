<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailSender extends Mailable
{
    use Queueable, SerializesModels;

    public $details;
    public $view_name;
    public $title;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($view_name, $details, $title)
    {
        $this->details   = $details;
        $this->view_name = $view_name;
        $this->title     = $title;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->title)->view($this->view_name);
    }
}
