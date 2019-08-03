<?php

namespace App\Models\User;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use App\Constants\UserConstants;

class User extends Authenticatable implements JWTSubject {
    use Notifiable;

    /**
     * Route notifications for the Slack channel.
     *
     * @return string
     */
    public function routeNotificationForSlack()
    {
        return config('services.slack.webhook_url');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
        'forgot_password_token', 'forgot_password_date'
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Hash raw user password when creating resource
     *
     * @param string $rawPassword
     * @return void
     */
    public function setPasswordAttribute(string $rawPassword) : void
    {
        $this->attributes['password'] = bcrypt($rawPassword);
    }

    /**
     * Search user by email
     *
     * @param Builder $query
     * @param string $email
     * @return void
     */
    public function scopeWithEmail(Builder $query, string $email) : Builder
    {
        return $query->where('email', $email);
    }

    /**
     * Remove token and date for reset password
     * from model
     *
     * @return void
     */
    public function resetForgotPasswordToken() : void
    {
        $this->forgot_password_token = null;
        $this->forgot_password_date = null;
    }

    /**
     * Generate email verification token
     *
     * @return void
     */
    public function generateVerifyToken()
    {
        $this->verify_token = Str::random(UserConstants::VERIFY_EMAIL_TOKEN_LENGTH);
    }
}
