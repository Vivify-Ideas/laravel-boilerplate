<?php

namespace App\Services\Chat;

use App\Models\User\User;
use \Chat;
use Musonza\Chat\Models\Conversation;
use App\Notifications\Socket\Chat\NewMessage;
use Musonza\Chat\Models\Message;
use Illuminate\Http\UploadedFile;
use App\Services\File\FilesService;
use App\Types\File\CompressImage;
use App\Constants\ConversationConstants;
use Illuminate\Database\Eloquent\Collection;

class ChatMessageService {

    /**
     * @var FilesService
     */
    private $_filesService;

    public function __construct(FilesService $filesService)
    {
        $this->_filesService = $filesService;
    }

    /**
     * Get all conversation messages
     *
     * @param Conversation $conversation
     * @return Collection
     */
    public function index(Conversation $conversation) : Collection
    {
        return $conversation->messages()->get();
    }

    /**
     * Send message to all users in conversation
     *
     * @param User $user
     * @param Conversation $conversation
     * @param string $body
     * @return Chat
     */
    public function store(
        User $user,
        Conversation $conversation,
        string $body = null,
        UploadedFile $image = null
    ) : Message {
        if ($image) {
            $body = $this->_uploadImage($image, $conversation->id);
        }

        $chat = Chat::message($body)
            ->from($user)
            ->to($conversation)
            ->send();

        $user->notify(new NewMessage($user, $conversation->id, $body));

        return $chat;
    }

    /**
     * Upload image to the server and return path
     *
     * @param UploadedFile $image
     * @param integer $conversationId
     * @return string
     */
    private function _uploadImage(UploadedFile $image, int $conversationId): string
    {
        return $this->_filesService->compressAndSaveImage(
            ConversationConstants::formatImagePath($conversationId),
            new CompressImage(
                $image,
                ConversationConstants::IMAGE_WIDTH,
                ConversationConstants::IMAGE_HEIGHT
            )
        );
    }
}
