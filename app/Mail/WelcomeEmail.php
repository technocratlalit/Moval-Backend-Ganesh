<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $maildata;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($maildata)
    {
        $this->maildata = $maildata;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $address = $this->maildata['contactEmail'];
        $subject = $this->maildata['subject'];
        $companyName = $this->maildata['from_name'];
        if (isset($this->maildata['reportUrl'])){
            return $this->view($this->maildata['mail_template'])
                ->from($address, $companyName)
                ->subject($subject)
                ->attach($this->maildata['reportUrl'])
                ->with($this->maildata);
        }else{
                return $this->view($this->maildata['mail_template'])
                ->from($address, $companyName)
                ->subject($subject)
                ->with($this->maildata);
        }

    }
}
