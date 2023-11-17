<?php

namespace App\Jobs;

use App\Mail\MailNotifyDefault;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendMailDefault implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $email;

    public $thongbao;

    public $content;

    public function __construct($email, $thongbao, $content)
    {
        $this->email = $email;
        $this->thongbao = $thongbao;
        $this->content = $content;
    }

    public function handle()
    {
        $email = $this->email;
        $thongbao = $this->thongbao;
        $content = $this->content;

        // Các bước xử lý logic liên quan đến email và token
        Mail::to($email)->send(new MailNotifyDefault($thongbao, $content));
        info("Email sent to $email with content: $content and message : $thongbao");
        Log::info("Email sent to $email with content: $content and message : $thongbao");
    }
}
