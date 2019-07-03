<?php

namespace App\Channels\Socket;

use Illuminate\Notifications\Notification;

class SocketChannel {

    /**
     * Socket gateway to socket service
     *
     * @var SocketGateway
     */
    private $_socketGateway;

    public function __construct(SocketGateway $socketGateway)
    {
        $this->_socketGateway = $socketGateway;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $this->_socketGateway->dispatch($notification);
    }
}
