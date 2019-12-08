<?php

namespace App\Services\User;

use App\Models\User\User;
use App\Services\File\FilesService;
use Illuminate\Http\UploadedFile;
use App\Constants\UserConstants;
use App\Types\File\CompressImage;
use App\Mail\User\VerifyEmailMail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class UserService {

    private $_filesService;

    /**
     * @param FilesService $filesService
     */
    public function __construct(FilesService $filesService)
    {
        $this->_filesService = $filesService;
    }

    /**
     * Change the password on the user that is passed
     * to the method
     *
     * @param User $user
     * @param string $newPassword
     * @return User
     */
    public function changePassword(User $user, string $newPassword) : User
    {
        $user->password = $newPassword;
        $user->save();

        return $user;
    }

    /**
     * Update user profile information and avatar if it's passed
     *
     * @param User $user
     * @param array $data
     * @return User
     */
    public function updateProfile(User $user, array $updateData, ?UploadedFile $avatarFile) : User
    {
        $user->update($updateData);

        if (!empty($avatarFile)) {
            $imagePath = $user->avatar ?? UserConstants::formatAvatarPath($user->id);
            $compressImage = new CompressImage(
                $avatarFile,
                UserConstants::AVATAR_WIDTH,
                UserConstants::AVATAR_HEIGHT
            );
            if ($user->avatar) {
                $this->_filesService->removeImage($user->avatar);
            }
            $user->avatar = $this->_filesService->compressAndSaveImage(
                $imagePath,
                $compressImage
            );

            $user->save();
        }

        return $user;
    }

    /**
     * Generate verification token, and send verification email
     * with that token
     *
     * @param User $user
     * @return void
     */
    public function generateAndSendVerifyEmail(User $user)
    {
        $user->generateVerifyToken();
        $user->save();
        $this->sendVerifyEmail($user);
    }

    /**
     * Generate verify token and send verification email
     *
     * @param User $user
     * @return void
     */
    public function sendVerifyEmail(User $user) : void
    {
        Mail::to($user->email)
            ->queue(
                new VerifyEmailMail($user->verify_token)
            );
    }

    /**
     * Verify user token, remove it from users table
     *
     * @param string $token
     * @return void
     */
    public function verifyEmail(string $token) : void
    {
        User::where('verify_token', $token)
            ->update([ 'verify_token' => null ]);
    }
}
