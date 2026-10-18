<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Mail\SendOTPEmail;

class OtpService
{
    public function generate(): int
    {
        return rand(100000, 999999);
    }

    public function send(string $email, int $otp): void
    {
        Mail::to($email)->send(new SendOTPEmail($otp));
    }
}