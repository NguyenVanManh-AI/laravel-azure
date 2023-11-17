<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordNewAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public $email;

    public $new_password;

    public function __construct($email, $new_password)
    {
        $this->email = $email;
        $this->new_password = $new_password;
    }

    public function build()
    {
        $subject = 'Thông báo từ hệ thống Elister Health Care';

        return $this->subject($subject)->view('emails.send_new_password')->with(['email' => $this->email, 'new_password' => $this->new_password]);
    }
}
