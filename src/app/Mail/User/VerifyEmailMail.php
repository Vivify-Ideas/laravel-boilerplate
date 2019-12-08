<?php
namespace App\Mail\User;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmailMail extends Mailable {
    use Queueable, SerializesModels;

    private $_token;

    /**
     * Create a new message instance.
     *
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->_token = $token;
    }

     /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $verifyTokenUrl = route('verifyEmail', [
            'verify_token' => $this->_token
        ]);

        return $this->subject(config('app.name') . ' - ' . trans('emails.verify_email_title'))
            ->view('emails.verify_email')
            ->with([
                'verifyTokenUrl' => $verifyTokenUrl,
            ]);
    }
}
