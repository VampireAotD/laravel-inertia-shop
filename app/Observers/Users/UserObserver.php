<?php

namespace App\Observers\Users;

use App\Models\User;
use App\Services\Admin\Interfaces\Images\ImageServiceInterface;

class UserObserver
{
    /**
     * @var ImageServiceInterface
     */
    private $imageService;

    public function __construct(ImageServiceInterface $imageService)
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
        rabbitmq()->sendMessage([
            'channel' => 'users',
            'method' => 'info',
            'message' => 'New user was registered',
            'additional_information' => [
                'user' => $user->name
            ]
        ], 'logs');
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\User $user
     * @return void
     */
    public function updated(User $user)
    {
        rabbitmq()->sendMessage([
            'channel' => 'users',
            'method' => 'info',
            'message' => 'User updated his information',
            'additional_information' => [
                'user' => $user->name
            ]
        ], 'logs');
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

        rabbitmq()->sendMessage([
            'channel' => 'users',
            'method' => 'warning',
            'message' => 'User was deleted by',
            'additional_information' => [
                'user' => $user->name,
                'deleted by' => request()->user()->name
            ]
        ], 'logs');
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
