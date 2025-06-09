<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookReport;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * O e-mail do destinatário.
     *
     * @var string
     */
    protected $email;

    /**
     * O assunto do e-mail.
     *
     * @var string
     */
    protected $subject;

    /**
     * Os dados do relatório.
     *
     * @var array
     */
    protected $reportData;

    /**
     * Create a new job instance.
     *
     * @param string $email
     * @param string $subject
     * @param array $reportData
     */
    public function __construct(string $email, string $subject, array $reportData)
    {
        $this->email = $email;
        $this->subject = $subject;
        $this->reportData = $reportData;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->email)
            ->send(new BookReport($this->subject, $this->reportData));
    }
}

