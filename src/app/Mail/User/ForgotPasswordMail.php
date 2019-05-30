<?php
namespace App\Mail\User;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordMail extends Mailable {
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
        $resetPasswordUrl = sprintf(
            config('app.mobile_app_url') . '?forgot_password_token=%s',
            $this->_token
        );

        return $this->subject(config('app.name') . ' - ' . trans('emails.password_reset_email_title'))
            ->view('emails.forgot_password')
            ->with([
                'resetPasswordUrl' => $resetPasswordUrl,
            ]);
    }
}
