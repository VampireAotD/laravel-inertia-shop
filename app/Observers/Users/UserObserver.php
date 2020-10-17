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
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        //
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        //
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        if ($this->imageService->deleteImagesWithFolderFromCDN($user, "products/$user->name",User::class)) {
            $this->imageService->deleteImagesFromDB($user);
            $user->forceFill([
                'profile_photo_path' => null
            ])->save();
        }
    }

    /**
     * Handle the user "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
