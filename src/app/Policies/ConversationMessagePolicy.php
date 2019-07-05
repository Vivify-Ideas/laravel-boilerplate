<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User\User;
use Musonza\Chat\Models\Conversation;

class ConversationMessagePolicy {
    use HandlesAuthorization;

    /**
     * Check is user part of conversation
     *
     * @param User $user
     * @param Conversation $conversation
     * @return boolean
     */
    public function partOfConversation(User $user, Conversation $conversation) : bool
    {
        return $conversation->users()->where('mc_conversation_user.user_id', $user->id)->exists();
    }
}
