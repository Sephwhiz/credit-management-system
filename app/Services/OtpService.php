<?php

namespace App\Services;

use App\Mail\OtpVerificationMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OtpService
{
    /**
     * Generate a 6-digit OTP code
     */
    public function generate(): string
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Send OTP via email using Laravel Mail + Resend
     */
    public function send(string $email, string $code): void
    {
        try {
            Mail::to($email)->send(new OtpVerificationMail($code));
            Log::info("OTP email sent successfully to {$email}");
        } catch (\Throwable $e) {
            Log::error("Failed to send OTP email to {$email}: " . $e->getMessage());
            throw $e; // Re-throw so controller can catch it
        }
    }
}