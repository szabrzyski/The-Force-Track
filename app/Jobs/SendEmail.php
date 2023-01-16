<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 120;

    public $backoff = 30;

    public $deleteWhenMissingModels = true;

    /**
     * Create a new job instance and put it on specific queue.
     *
     * @param  string  $emailAddress
     * @param  Mailable  $email
     * @return void
     */
    public function __construct(public string $emailAddress, public Mailable $email)
    {
        $this->onQueue('emails');
    }

    /**
     * Send the e-mail.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->emailAddress)->send($this->email);
    }
}
