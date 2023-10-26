<?php

namespace App\Jobs;

use App\Mail\MailNotify;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendMailNotify implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $email;

    public $content;

    public function __construct($email, $content)
    {
        $this->email = $email;
        $this->content = $content;
    }

    public function handle()
    {
        $email = $this->email;
        $content = $this->content;

        // Các bước xử lý logic liên quan đến email và token
        Mail::to($email)->send(new MailNotify($content));
        info("Email sent to $email with URL: $content");
        Log::info("Email sent to $email with URL: $content");
    }
}
