<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyMailCustomer extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $token;
    public function __construct($_token)
    {
        //
        $this->token = $_token;

    }
 
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $url = url('http://localhost:8080/main/reset-password?token=' . $this->token);
        return $this->view('emails.contentMail',['__url' => $url]);
    }
}
