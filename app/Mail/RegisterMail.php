<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegisterMail extends Mailable
{
    use Queueable, SerializesModels;
    public $name;
    public $hash;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $hash)
    {
        $this->name = $name;
        $this->hash = $hash;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('CariPengalaman.com')->view('email.register');
    }
}
