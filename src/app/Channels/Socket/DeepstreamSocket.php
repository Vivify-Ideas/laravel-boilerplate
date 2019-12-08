<?php

namespace App\Channels\Socket;

use App\Notifications\Socket\SocketEvent;
use Deepstreamhub\DeepstreamClient;

class DeepstreamSocket implements SocketGateway {
    private $_client;

    /**
     * Class constructor
     */
    public function __construct()
    {
        try {
            $this->_client = new DeepstreamClient(config('websockets.url'), []);
        } catch (\Exception $ex) {
            \Log::error($ex->getMessage());
        }
    }

    public function dispatch(SocketEvent $event) : void
    {
        $data = $event->getSocketData();
        try {
            $this->_client->emitEvent($event->getSocketChannel(), $data);
        } catch (\Exception $ex) {
            \Log::error($ex->getMessage());
        }
    }
}
