<?php

namespace App\Notifications\PushNotifications;

class WelcomeNotification extends ExpoNotification {
    protected $title = 'Welcome';

    protected $body = 'Welcome to the Application';
}
