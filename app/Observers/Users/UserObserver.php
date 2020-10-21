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
        \Log::channel('users')->info('New user was registered', [
            'user' => $user->name
        ]);
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\User $user
     * @return void
     */
    public function updated(User $user)
    {
        \Log::channel('users')->info('User updated his information', [
            'user' => $user->name
        ]);
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

            \Log::channel('users')->warning('User was deleted by', [
                'user' => $user->name,
                'deleted by' => request()->user()->name
            ]);
        }
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
