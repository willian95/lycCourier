<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\User;

class SendWelcomeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $userId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $user = User::find($this->userId);

        $to_name = $user->name;
        $to_email = $user->email;
    
        $data = ["user" => $user];

        \Mail::send("emails.welcomeEmail", $data, function($message) use ($to_name, $to_email) {

            $message->to($to_email, $to_name)->subject("¡Nueva plataforma!");
            $message->from(env("MAIL_FROM_ADDRESS"), env("MAIL_FROM_NAME"));

        });
    }
}
