<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\InquiryMail;

class SendInquiryTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:inquiry-test {recipient=interntoyo@gmail.com} {from?} {attachmentPath?} {attachmentName?} {attachmentMime?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test inquiry email to the given address';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $recipient = $this->argument('recipient');
        $from = $this->argument('from');
        $attachmentPath = $this->argument('attachmentPath');
        $attachmentName = $this->argument('attachmentName');
        $attachmentMime = $this->argument('attachmentMime');

        $data = [
            'name' => 'Test Sender',
            'email' => $from ?? 'test@example.com',
            'subject' => 'Test Inquiry',
            'message' => 'This is a test inquiry sent by the artisan command.'
        ];

        if ($attachmentPath) {
            $data['attachment_path'] = $attachmentPath;
            $data['attachment_original_name'] = $attachmentName ?: basename($attachmentPath);
            $data['attachment_mime'] = $attachmentMime ?: 'application/octet-stream';
        }

        try {
            Mail::to($recipient)->send(new InquiryMail($data));
            $this->info("Test inquiry sent to {$recipient} (from: {$data['email']})");
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to send test inquiry: ' . $e->getMessage());
            return 1;
        }
    }
}
