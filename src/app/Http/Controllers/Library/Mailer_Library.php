<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Library\Base_Library as Base;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailSender as Sender;

class Mailer_Library extends Base
{
    private $config;

    public function __construct()
    {
        parent::__construct();
        $this->config = $this->get_yaml['SYSTEM'];
    }

    public function send_email($title, $to_email, $data, $view_template)
    {
        try {

            $detail  = ['title' => $title, 'data' => $data];
            $sender  = new Sender($view_template, $detail, $title);
            $sending = Mail::to($to_email)->send($sender);

            return [
                'status' => 1
            ];


        } catch (\Exception $e) {

            return [
                'status'  => 0,
                'message' => $e->getMessage()
            ];
        }

    }
}
