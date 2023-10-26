<?php

namespace App\Jobs;

use App\Mail\PasswordNewAdmin;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendPasswordNewAdmin implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $email;

    public $new_password;

    public function __construct($email, $new_password)
    {
        $this->email = $email;
        $this->new_password = $new_password;
    }

    public function handle()
    {
        $email = $this->email;
        $new_password = $this->new_password;

        // Các bước xử lý logic liên quan đến email và token
        Mail::to($email)->send(new PasswordNewAdmin($email, $new_password));
        info("Email sent to $email with URL: $email");
        Log::info("Email sent to $email with URL: $email");
    }
}
