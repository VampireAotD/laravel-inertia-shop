<?php

namespace App\Observers\Users;

use App\DTO\RabbitMq\LogMessageDto;
use App\Models\User;
use App\Services\Admin\Images\ImageService;

class UserObserver
{
    /**
     * @var ImageService
     */
    private $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\User $user
     * @return void
     */
    public function created(User $user)
    {
        $message = new LogMessageDto('users', 'info','New user was registered', [
            'user' => $user->name
        ]);

        rabbitmq()->sendMessage($message, 'logs');
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\User $user
     * @return void
     */
    public function updated(User $user)
    {
        $message = new LogMessageDto('users', 'info','User updated his information', [
            'user' => $user->name
        ]);

        rabbitmq()->sendMessage($message, 'logs');
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param  \App\Models\User $user
     * @return void
     */
    public function deleted(User $user)
    {
        if ($this->imageService->deleteImagesWithFolderFromCDN($user, "users/$user->name", User::class)) {
            $this->imageService->deleteImagesFromDB($user);

            $user->forceFill([
                'profile_photo_path' => null
            ])->save();
        }

        $message = new LogMessageDto('users', 'warning','User was deleted by', [
            'user' => $user->name,
            'deleted by' => request()->user()->name
        ]);

        rabbitmq()->sendMessage($message, 'logs');
    }

    /**
     * Handle the user "restored" event.
     *
     * @param  \App\Models\User $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param  \App\Models\User $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
