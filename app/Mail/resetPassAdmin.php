<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class resetPassAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public $User, $Token;

    /**
     * Create a new message instance.
     *
     * @param $User
     * @param $Token
     */
    public function __construct($User, $Token)
    {
        $this->User = $User;
        $this->Token = $Token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //return $this->view('emails.resetPassword', compact('Admin'));
        return $this->from('RS-School@gmail.com')
            ->subject('Password reset')
            ->markdown('emails.resetPassAdmin')
            ->with(['User'=> $this->User, 'Token'=> $this->Token]);
    }
}
