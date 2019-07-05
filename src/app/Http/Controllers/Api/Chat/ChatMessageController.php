<?php

namespace App\Http\Controllers\Api\Chat;

use App\Services\Chat\ChatMessageService;
use Musonza\Chat\Models\Conversation;
use App\Http\Requests\Chat\NewChatMessageRequest;

class ChatMessageController {
    /**
     * Chat Service
     *
     * @var ChatMessageService
     */
    private $_chatMessageService;

    public function __construct(ChatMessageService $chatMessageService)
    {
        $this->_chatMessageService = $chatMessageService;
    }

    /**
     * @SWG\Get(
     *   tags={"Chat"},
     *   path="/chats/{conversation}",
     *   summary="Get all messages in conversation",
     *   operationId="conversationMessages",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="conversation",
     *     in="path",
     *     required=true,
     *     type="integer"
     *   ),
     *   security={{"authorization_token":{}}},
     *   @SWG\Response(response=200, description="Successful operation"),
     *   @SWG\Response(response=401, description="Unauthorized"),
     *   @SWG\Response(response=422, description="Validation failed"),
     *   @SWG\Response(response=500, description="Internal server error")
     * )
     *
     * @param Conversation $conversation
     */
    public function index(Conversation $conversation)
    {
        return $this->_chatMessageService->index($conversation);
    }

    /**
     * @SWG\Post(
     *   tags={"Chat"},
     *   path="/chats/{conversation}/messages",
     *   summary="Send message to the conversation",
     *   operationId="conversationSendMessage",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="conversation",
     *     in="path",
     *     required=true,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="body",
     *     in="formData",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="image",
     *     in="formData",
     *     required=false,
     *     type="file"
     *   ),
     *   security={{"authorization_token":{}}},
     *   @SWG\Response(response=200, description="Successful operation"),
     *   @SWG\Response(response=401, description="Unauthorized"),
     *   @SWG\Response(response=422, description="Validation failed"),
     *   @SWG\Response(response=500, description="Internal server error")
     * )
     *
     * @param NewChatMessageRequest $request
     * @param Conversation $conversation
     */
    public function store(NewChatMessageRequest $request, Conversation $conversation)
    {
        return $this->_chatMessageService->store(
            auth()->user(),
            $conversation,
            $request->get('body'),
            $request->file('image')
        );
    }
}
