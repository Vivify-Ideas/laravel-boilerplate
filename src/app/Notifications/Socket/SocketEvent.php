<?php

namespace App\Notifications\Socket;

use Illuminate\Notifications\Notification;
use App\Channels\Socket\SocketChannel;

abstract class SocketEvent extends Notification {

    /**
     * Socket event nane
     *
     * @var string
     */
    protected $eventName;

    /**
     * Returns all data that needs to be sent
     * over websocket connection
     *
     * @return array
     */
    abstract public function getSocketData(): array;

    /**
     * Returns the websocket channel on which event is sent
     *
     * @return string
     */
    abstract public function getSocketChannel(): string;

    public function via($notifiable)
    {
        return [
            SocketChannel::class
        ];
    }
}
