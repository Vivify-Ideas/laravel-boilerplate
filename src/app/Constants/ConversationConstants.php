<?php

namespace App\Constants;

use Illuminate\Support\Str;

class ConversationConstants {
    const IMAGE_PATH = '/conversations/';

    const IMAGE_EXTENSION = 'jpg';

    const IMAGE_WIDTH = 200;

    const IMAGE_HEIGHT = 200;

    /**
     * Format the avatar storage path
     *
     * @param integer $conversationId
     * @return string
     */
    public static function formatImagePath(int $conversationId) : string
    {
        return self::IMAGE_PATH.$conversationId.'/'.Str::random().'.'.self::IMAGE_EXTENSION;
    }
}
