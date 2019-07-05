<?php

namespace App\Services\Chat;

use App\Models\User\User;
use \Chat;
use Musonza\Chat\Models\Conversation;

class ChatService {

    const MAX_CONVERSATIONS_PER_PAGE = 10;

    /**
     * Returns all user conversations
     *
     * @param User $user
     * @return Collection
     */
    public function index(User $user, int $page) : array
    {
        $conversations = Chat::conversations()
            ->setUser($user)
            ->limit(self::MAX_CONVERSATIONS_PER_PAGE)
            ->page($page)
            ->get()
            ->items();

        return array_map(function ($conversation) {
            $conversation->load('users');
            return $conversation;
        }, $conversations);
    }

    /**
     * Start conversation for users
     *
     * @param User $user
     * @param array $users
     * @return void
     */
    public function startConversation(User $user, array $users) : Conversation
    {
        array_push($users, $user->id);
        $conversation = Chat::createConversation([ $users ])->makePrivate();

        return $conversation;
    }
}
