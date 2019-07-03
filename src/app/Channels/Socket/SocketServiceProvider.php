<?php

namespace App\Channels\Socket;

use Illuminate\Support\ServiceProvider;

class SocketServiceProvider extends ServiceProvider {
    public function boot()
    {
        $this->app->singleton(
            'App\Channels\Socket\SocketGateway',
            DeepstreamSocket::class
        );
    }
}
