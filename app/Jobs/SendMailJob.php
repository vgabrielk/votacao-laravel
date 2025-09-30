<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailable;

class SendMailJob implements ShouldQueue
{
    use Dispatchable;
    use Queueable;
    use SerializesModels;

    public Mailable $mailable;
    public string $to;

    /**
     * Create a new job instance.
     *
     * @param string $to Email do destinatário
     * @param Mailable $mailable Qualquer Mailable
     */
    public function __construct(string $to, Mailable $mailable)
    {
        $this->to = $to;
        $this->mailable = $mailable;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->to)->send($this->mailable);
    }
}
