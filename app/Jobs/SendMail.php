<?php

namespace App\Jobs;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Log;

class SendMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $recipient;
    public $cc;
    public $mailData;
    public function __construct($to,$mailData,$cc='')
    {
        $this->recipient = $to; 
        $this->mailData = $mailData; 
        $this->cc = $cc; 
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            if(!empty($this->cc)){
                Mail::to($this->recipient)->cc($this->cc)->send(new WelcomeEmail($this->mailData));  
            }else{
                Mail::to($this->recipient)->send(new WelcomeEmail($this->mailData));  
            }
            
         } catch (\Exception $e) {
             Log::error('Send Email Job failed: ' . $e->getMessage());
         } 
        
    }
}
