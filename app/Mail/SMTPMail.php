<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class SMTPMail extends Mailable
{
    public $subject;
    public $body;

    public function __construct(string $subject, string $body)
    {
        $this->subject = $subject;
        $this->body = $body;
    }

    public function build()
    {
        return $this->subject($this->subject)
                    ->html($this->body);  // If you're sending HTML emails, use `html()`
    }
}
