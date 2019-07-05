<?php

namespace App\Notifications\Socket\Chat;

use App\Constants\SocketConstants;
use App\Models\User\User;
use App\Notifications\Socket\SocketEvent;

class NewMessage extends SocketEvent {
    protected $eventName = 'newMessage';

    /**
     * @var User $user
     */
    private $_sender;

    /**
     * Message body
     *
     * @var string
     */
    private $_body;

    /**
     * Conversation id on which we send websocket
     * Used to format channel
     *
     * @var int
     */
    private $_conversationId;

    public function __construct(User $sender, int $conversationId, string $body)
    {
        $this->_sender = $sender;
        $this->_body = $body;
        $this->_conversationId = $conversationId;
    }

    public function getSocketData(): array
    {
        return [
            'event' => $this->eventName,
            'body' => $this->_body,
            'conversation_id' => $this->_conversationId,
            'sender' => [
                'id' => $this->_sender->id,
                'first_name' => $this->_sender->first_name,
                'last_name' => $this->_sender->last_name
            ]
        ];
    }

    public function getSocketChannel(): string
    {
        return SocketConstants::CONVERSATION_CHANNEL.$this->_conversationId;
    }
}
