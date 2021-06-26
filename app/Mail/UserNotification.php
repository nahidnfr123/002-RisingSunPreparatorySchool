<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $User;
    public $Subject;
    public $Message;

    /**
     * Create a new message instance.
     *
     * @param $User
     * @param $Subject
     * @param $Message
     */
    public function __construct($User, $Subject, $Message)
    {
        $this->User = $User;
        $this->Subject = $Subject;
        $this->Message = $Message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //return $this->markdown('emails.userNotification')->with('User', $this->User);
        /*return $this->subject($this->Subject)
            ->markdown('emails.userNotification')
            ->with('User', $this->User);*/

        return $this->from('RS-School@gmail.com')
            ->subject($this->Subject)
            ->markdown('emails.userNotification')
            ->with(['User' => $this->User, 'Subject' => $this->Subject, 'Message' => $this->Message]);
    }
}
