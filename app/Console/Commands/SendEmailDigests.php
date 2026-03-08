<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendEmailDigests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-email-digests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send batched email digests to users with hourly or daily preferences.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentHour = now()->hour;

        $users = \App\Models\User::where('notify_email', true)
            ->whereIn('email_frequency', ['hourly', 'daily'])
            ->get();

        foreach ($users as $user) {
            // Check frequency logic
            if ($user->email_frequency === 'daily' && $currentHour !== 8) {
                continue; // Send daily digest only at 8 AM
            }

            $notifications = $user->unreadNotifications;

            if ($notifications->isEmpty()) {
                continue;
            }

            // Send Email Digest
            \Illuminate\Support\Facades\Mail::to($user)->send(new \App\Mail\EmailDigest($user, $notifications));

            // Mark these specific notifications as read so they aren't emailed again next time
            $notifications->markAsRead();
            
            $this->info("Sent digest to {$user->email} containing {$notifications->count()} notifications.");
        }

        $this->info('Email digests processed successfully.');
    }
}
