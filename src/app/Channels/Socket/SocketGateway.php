<?php

namespace App\Channels\Socket;

use App\Notifications\Socket\SocketEvent;

interface SocketGateway {
    public function dispatch(SocketEvent $socketEvent);
}
