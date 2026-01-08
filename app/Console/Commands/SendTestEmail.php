<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTestEmail extends Command
{
    protected $signature = 'mail:test {to?} {--subject=Test Email} {--message=Hello from AsBeez}';
    protected $description = 'Send a simple test email using current mail configuration (SMTP/Mailpit)';

    public function handle(): int
    {
        $to = $this->argument('to') ?? 'test@example.com';
        $subject = (string) $this->option('subject');
        $message = (string) $this->option('message');

        $fromAddress = (string) config('mail.from.address');
        $fromName = (string) config('mail.from.name');

        try {
            Mail::raw($message, function ($mail) use ($to, $subject, $fromAddress, $fromName) {
                $mail->to($to)->subject($subject);
                if ($fromAddress) {
                    $mail->from($fromAddress, $fromName ?: null);
                }
            });
        } catch (\Throwable $e) {
            $this->error('Failed to send test email: '.$e->getMessage());
            return self::FAILURE;
        }

        $this->info("Test email sent to {$to}. Check Mailpit UI.");
        return self::SUCCESS;
    }
}
