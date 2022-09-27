<?php
 
namespace App\Mail;
 
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
 
class NotifyMail extends Mailable
{
    use Queueable, SerializesModels;
 
    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $code;
    public function __construct($_code)
    {
        //
        $this->code = $_code;

    }
 
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.contentMail',['__code' => $this->code]);
    }
}