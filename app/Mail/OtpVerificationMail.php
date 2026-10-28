<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $code;

    public function __construct($code)
    {
        $this->code = $code;
    }

    public function build()
    {
        return $this->subject('Your OTP Verification Code')
            ->html("<div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                <h2>Credit Management System</h2>
                <p>Your verification code is:</p>
                <div style='background: #f0f9ff; padding: 20px; border-radius: 8px; text-align: center; font-size: 32px; letter-spacing: 8px; font-weight: bold; color: #0369a1;'>
                    {$this->code}
                </div>
                <p style='color: #666; margin-top: 20px;'>This code expires in 24 hours.</p>
            </div>");
    }
}