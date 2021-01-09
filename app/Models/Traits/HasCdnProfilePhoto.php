<?php

namespace App\Models\Traits;

use App\Services\Admin\Images\ImageService;
use Illuminate\Http\UploadedFile;

trait HasCdnProfilePhoto
{
    /**
     * Update the user's profile photo.
     *
     * @param \Illuminate\Http\UploadedFile $photo
     * @return void
     */
    public function updateProfilePhoto(UploadedFile $photo)
    {
        tap($this->profile_photo_path, function ($previous) use ($photo) {
            $imageService = app(ImageService::class);

            $this->forceFill([
                'profile_photo_path' => $imageService->createImage($this, $this->className(), $this->profilePhotoFolder(), $photo)->path
            ])->save();

            if ($previous) {
                $imageService->deleteImagesWithFolderFromCDN($this, $this->profilePhotoFolder(), $this->className());
                $imageService->deleteImagesFromDB($this);

                $this->forceFill([
                    'profile_photo_path' => $imageService->createImage($this, $this->className(), $this->profilePhotoFolder(), $photo)->path
                ])->save();
            }
        });
    }

    /**
     * Delete the user's profile photo.
     *
     * @return void
     */
    public function deleteProfilePhoto()
    {
        $imageService = app(ImageService::class);

        if ($this->image()->first()) {
            $imageService->deleteImage($this->image()->first());
        }

        $this->forceFill([
            'profile_photo_path' => null,
        ])->save();
    }

    /**
     * Get the URL to the user's profile photo.
     *
     * @return string
     */
    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_path
            ? $this->profile_photo_path
            : $this->defaultProfilePhotoUrl();
    }

    /**
     * Get the default profile photo URL if no profile photo has been uploaded.
     *
     * @return string
     */
    public function defaultProfilePhotoUrl()
    {
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    /**
     * Get the folder that profile photos should be stored on.
     *
     * @return string
     */
    public function profilePhotoFolder()
    {
        return config('cloudinary-variables.user-default-folder');
    }

    /**
     * Get class for current instance
     *
     * Can be replaced in PHP 8 with : $this::class
     *
     * Should be removed or marked as deprecated in PHP 8
     *
     * @return string
     */
    public function className()
    {
        return get_class($this);
    }
}
