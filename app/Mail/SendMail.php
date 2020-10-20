<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    public  $title;
    public $customer_details;
    public $order_details;
    
    public function __construct($title, $customer_details, $order_details) { 

         $this->title = $title; 
         $this->customer_details= $customer_details; 
         $this->order_details = $order_details; 
        }


   public function build() { 
       return $this->subject($this->title) ->view('customer_mail'); 
    }

}
