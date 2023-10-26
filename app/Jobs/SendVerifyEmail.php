<?php

namespace App\Jobs;

use App\Mail\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendVerifyEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $email;

    public $url;

    public function __construct($email, $url)
    {
        $this->email = $email;
        $this->url = $url;
    }

    public function handle()
    {
        $email = $this->email;
        $url = $this->url;

        // Các bước xử lý logic liên quan đến email và token
        Mail::to($email)->send(new VerifyEmail($url));
        info("Email sent to $email with URL: $url");
        Log::info("Email sent to $email with URL: $url");
    }
}
