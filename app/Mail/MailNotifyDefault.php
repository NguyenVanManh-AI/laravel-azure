<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailNotifyDefault extends Mailable
{
    use Queueable, SerializesModels;

    public $thongbao;

    public $content;

    public function __construct($thongbao, $content)
    {
        $this->thongbao = $thongbao;
        $this->content = $content;
    }

    public function build()
    {
        $thongbao = $this->thongbao;
        $content = $this->content;
        $subject = 'Thông báo từ hệ thống Elister Health Care';

        return $this->subject($subject)->view('emails.mail_notify_default', compact(['thongbao', 'content']));
    }
}
