<?php

namespace App\Repositories\Admin\Images;

use App\Models\Image;
use App\Repositories\BaseRepository;

class ImageRepository extends BaseRepository implements ImageRepositoryInterface
{
    /**
     * Identifies model for current repository
     *
     * @return string
     */
    public function setRepositoryModel(): string
    {
        return Image::class;
    }

    /**
     * Return one entity by id
     *
     * @param int $id
     * @return mixed
     */
    public function findItemById(int $id)
    {
        return $this->startConditions()->where('id', $id)->firstOrFail();
    }
}