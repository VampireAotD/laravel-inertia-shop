<?php

namespace App\Services\Admin\Interfaces\Images;

use Illuminate\Database\Eloquent\Model;

interface ImageServiceInterface
{
    /**
     * Removes entry/ies from database by its relation
     *
     * @param Model $model
     * @return bool
     */
    public function deleteImagesFromDB(Model $model) : bool;

    /**
     * Deletes images and entity folder from CDN storage
     *
     * @param string $folder
     * @param string $model_type
     * @param Model $model
     * @return bool
     */
    public function deleteImagesWithFolderFromCDN(string $folder, string $model_type, Model $model) : bool;
}