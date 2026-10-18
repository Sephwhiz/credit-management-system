<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendOTPEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;

    public function __construct(int $otp)
    {
        $this->otp = $otp;
    }

    public function build()
    {
        return $this->subject('Your Verification Code')
                    ->view('emails.otp');
    }
}