<?php

namespace App\Models\User;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable implements JWTSubject {
    use Notifiable;

    const PASSWORD_LENGTH = 191;

    const SOCIAL_FACEBOOK = 'facebook';
    const SOCIAL_GOOGLE = 'google';
    const SOCIAL_LOGINS = [
        self::SOCIAL_FACEBOOK,
        self::SOCIAL_GOOGLE
    ];


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
        'first_name', 'last_name', 'email', 'password', 'social_id', 'social_type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
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
     * @return void
     */
    public function setPasswordAttribute($rawPassword)
    {
        $this->attributes['password'] = bcrypt($rawPassword);
    }

    /**
     * Search user by email
     *
     * @param Builder $query
     * @param string $email
     * @return Builder
     */
    public function scopeWithEmail(Builder $query, string $email) : Builder
    {
        return $query->where('email', $email);
    }
}
