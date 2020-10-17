<?php

namespace App\Services\Admin\Interfaces\Images;

use App\Models\Product;
use App\Models\Slide;
use App\Models\User;

interface ImageServiceInterface
{
    /**
     * Removes entry/ies from database by its relation
     *
     * @param Product|Slide|User $model
     * @return bool
     */
    public function deleteImagesFromDB($model): bool;

    /**
     * Deletes images and entity folder from CDN storage
     *
     * @param Product|Slide|User $model
     * @param string $folder
     * @param string $model_type
     * @return bool
     */
    public function deleteImagesWithFolderFromCDN($model, string $folder, string $model_type): bool;
}