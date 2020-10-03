<?php

namespace App\Services\Admin\Images;

use App\Models\Image;
use App\Services\Admin\Interfaces\Images\ImageServiceInterface;
use Cloudinary\Api\Admin\AdminApi;
use Cloudinary\Api\Upload\UploadApi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ImageService implements ImageServiceInterface
{
    /**
     * Creates an Image instance
     *
     * Mostly used to upload images on Cloudinary
     *
     * Variable $model_type can be removed with PHP 8 : $variable_name::class
     *
     * Variable folder is used for storage folder on Cloudinary
     *
     * For example : products/product-1/product-image-1.jpg, slides/slide-1.jpg
     *
     * Variable $image is instance of UploadedFile class, but the method storeOnCloudinary() came from CloudinaryServiceProvider
     *
     * @param Model $model
     * @param string $model_type
     * @param int $number
     * @param string $folder
     * @param UploadedFile $image
     * @return Image
     */
    public function createImage(Model $model, string $model_type, int $number = 0, string $folder, UploadedFile $image): Image
    {
        $storage_folder = $this->makeStorageFolder($folder, $model->slug);
        $alias = $this->makeImageAlias();

        return Image::create([
            'model_type' => $model_type,
            'model_id' => $model->id,
            'is_main' => $this->isMainImage($number),
            'alias' => $storage_folder . '/' . $alias,
            'path' => $image->storeOnCloudinaryAs(
                $storage_folder,
                $alias
            )->getSecurePath()
        ]);
    }

    /**
     * Removes entry/ies from database
     *
     * @param Model $model
     * @return bool
     */
    public function deleteImagesFromDB(Model $model): bool
    {
        return $model->images()->delete();
    }

    /**
     * Deletes images and entity folder from Cloudinary storage
     *
     * @param string $folder
     * @param string $model_type
     * @param Model $model
     * @return bool
     * @throws \Cloudinary\Api\Exception\ApiError
     */
    public function deleteImagesWithFolderFromCDN(string $folder, string $model_type, Model $model): bool
    {
        $images = collect(Image::modelImages($model_type, $model->id)->get())->pluck('alias')->toArray();

        $api = new AdminApi();
        if ($api->deleteResources($images) && $api->deleteFolder($folder)) {
            return true;
        }

        return false;
    }

    public function updateMainImage(Image $image)
    {
        Image::modelImages($image->model_type, $image->model_id)->get()->map(function ($image){
            $image->update([
                'is_main' => 0
            ]);
        });

        return $image->update([
           'is_main' => 1
        ]);
    }

    /**
     * Check if current image is first in list than it will be the main image
     *
     * @param $number
     * @return bool
     */
    private function isMainImage($number): bool
    {
        return $number === 0 ? true : false;
    }

    /**
     * Makes an alias for storage folder on Cloudinary
     *
     * @param string $folder
     * @param string $modelSlug
     * @return string
     */
    private function makeStorageFolder(string $folder, string $modelSlug)
    {
        return "$folder/$modelSlug";
    }

    /**
     * Makes image alias for Cloudinary
     *
     * @return string
     */
    private function makeImageAlias(): string
    {
        return Str::random();
    }
}