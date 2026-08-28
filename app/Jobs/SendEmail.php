<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\DynamicMail ;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $subject;
    protected $body;
    protected $recipient;

   
    public function __construct($subject,$body,$recipient)
    {
        $this->subject = $subject;
        $this->body = $body;
        $this->recipient = $recipient;
    }

   /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Send email using the DynamicMail Mailable
        Mail::send(new DynamicMail($this->subject, $this->body,$this->recipient));
    }

     /**
     * Handle a job failure.
     *
     * @return void
     */
    public function failed(\Exception $exception)
    {
        // Handle job failure (e.g., log the error, send an alert)
        Log::error('SendEmail job failed: ' . $exception->getMessage());

        // You could also notify the admin or take further actions on failure
        // Example: Mail::to('admin@example.com')->send(new FailedJobNotification($exception));
    }
}
