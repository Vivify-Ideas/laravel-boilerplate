<?php

namespace App\Http\Controllers\Api\Chat;

use App\Services\Chat\ChatService;
use App\Http\Requests\Chat\StartChatRequest;
use Illuminate\Http\Request;

class ChatController {
    /**
     * Chat Service
     *
     * @var ChatService
     */
    private $_chatService;

    public function __construct(ChatService $chatService)
    {
        $this->_chatService = $chatService;
    }

    /**
     * @SWG\Get(
     *   tags={"Chat"},
     *   path="/chats",
     *   summary="Get all user conversations",
     *   operationId="chats",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="page",
     *     in="query",
     *     description="ex. 1",
     *     required=false,
     *     type="integer"
     *   ),
     *   security={{"authorization_token":{}}},
     *   @SWG\Response(response=200, description="Successful operation"),
     *   @SWG\Response(response=401, description="Unauthorized"),
     *   @SWG\Response(response=422, description="Validation failed"),
     *   @SWG\Response(response=500, description="Internal server error")
     * )
     *
     * @param Request $request
     */
    public function index(Request $request)
    {
        return $this->_chatService->index(
            auth()->user(),
            $request->get('page') ?? 1
        );
    }

    /**
     * @SWG\Post(
     *   tags={"Chat"},
     *   path="/chats/start",
     *   summary="Start chat with following users",
     *   operationId="chatStart",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="users",
     *     in="body",
     *     required=true,
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(
     *           property="users",
     *           type="array",
     *           @SWG\Items(type="integer")
     *         )
     *     )
     *   ),
     *   security={{"authorization_token":{}}},
     *   @SWG\Response(response=200, description="Successful operation"),
     *   @SWG\Response(response=401, description="Unauthorized"),
     *   @SWG\Response(response=422, description="Validation failed"),
     *   @SWG\Response(response=500, description="Internal server error")
     * )
     *
     * @param Request $request
     */
    public function store(StartChatRequest $request)
    {
        return $this->_chatService->startConversation(
            auth()->user(),
            $request->get('users')
        );
    }
}
