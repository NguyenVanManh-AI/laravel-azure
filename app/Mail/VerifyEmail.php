<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function build()
    {
        $subject = 'Thông báo từ hệ thống Elister Health Care';

        return $this->subject($subject)->view('emails.verify_email')->with('url', $this->url);
    }
}
