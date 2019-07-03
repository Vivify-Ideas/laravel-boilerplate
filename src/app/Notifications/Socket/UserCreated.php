<?php

namespace App\Notifications\Socket;

use App\Constants\SocketConstants;
use App\Models\User\User;

class UserCreated extends SocketEvent {
    protected $eventName = 'newUser';

    /**
     * @var User $user
     */
    private $_user;

    public function __construct(User $user)
    {
        $this->_user = $user;
    }

    public function getSocketData(): array
    {
        return [
            'event' => $this->eventName,
            'user' => [
                'first_name' => $this->_user->first_name,
                'last_name' => $this->_user->last_name
            ]
        ];
    }

    public function getSocketChannel(): string
    {
        return SocketConstants::PUBLIC_CHANNEL;
    }
}
