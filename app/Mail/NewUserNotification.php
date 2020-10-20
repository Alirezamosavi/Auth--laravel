<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class NewUserNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('mosavialireza191@gmail.com', 'Mailtrap')
            ->subject('Mailtrap Confirmation')
            ->markdown('mailss')
            ->with([
                'name' => 'New Mailtrap User',
                'link' => 'http://127.0.0.1:4200/login'
            ]);
    }
}
