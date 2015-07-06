<?php
namespace App\Listeners\PasswordResetRequested;


use App\Events\PasswordResetRequested;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailPasswordResetLink  implements ShouldQueue {

    protected $mailer;
    protected $db;

    public function __construct(Mailer $mailer)
    {
        //
        $this->mailer = $mailer;
        $this->db = app('db');
    }

    /**
     * Handle the event.
     *
     * @param  PasswordResetRequested  $event
     * @return void
     */
    public function handle(PasswordResetRequested $event)
    {
        $email = $event->email;

        $token = str_random(100);

        $params = [
            'email' => $email,
            'token' => $token
        ];

        $this->db->table('password_resets')->insert($params);

        $url = env('CONSUMER_URL').'/reset-password?'.http_build_query($params);

        app('log')->info($url);

        $this->mailer->send(
            'emails.reset-password',
            [
                'url' => $url
            ],
            function($message) use ($email) {

                $message->subject('Reset your Reflect password');
                $message->to($email);

            });
    }

}