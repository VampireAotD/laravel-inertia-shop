<?php

namespace App\Services\Admin\Images;

use App\DTO\RabbitMq\LogMessageDto;
use App\Models\Image;
use App\Models\Product;
use App\Models\Slide;
use App\Models\User;
use Cloudinary\Api\Admin\AdminApi;
use Cloudinary\Api\Exception\ApiError;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ImageService
{
    private $api;

    public function __construct(AdminApi $adminApi)
    {
        $this->api = $adminApi;
    }

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
     * @param Product|Slide|User $model
     * @param string $model_type
     * @param int $number
     * @param string $folder
     * @param UploadedFile $image
     * @return Image
     */
    public function createImage($model, string $model_type, string $folder, UploadedFile $image, int $number = 0): Image
    {
        $storage_folder = $this->makeStorageFolder($folder, $model->slug ?? $model->name);
        $alias = $this->makeImageAlias();

        $message = new LogMessageDto('images', 'info', 'Set new image for model', [
            'model id' => $model->id,
            'model type' => $model_type,
        ]);

        rabbitmq()->sendMessage($message, 'logs');

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
     * @param Product|Slide|User $model
     * @return bool
     */
    public function deleteImagesFromDB($model): bool
    {
        if (method_exists($model, 'images')) {
            $images = $model->images();
        } else {
            $images = $model->image();
        }

        return $images->delete();
    }

    /**
     * Deletes images and entity folder from CDN storage
     *
     * @param Product|Slide|User $model
     * @param string $folder
     * @param string $model_type
     * @return bool
     */
    public function deleteImagesWithFolderFromCDN($model, string $folder, string $model_type): bool
    {
        try {
            $images = collect(Image::modelImages($model_type, $model->id)->get())->pluck('alias')->toArray();

            if ($images) {
                if ($this->api->deleteResources($images) && $this->api->deleteFolder($folder)) {
                    return true;
                }
            }
        } catch (ApiError $exception) {
            $message = new LogMessageDto('images', 'warning', 'CDN exception while deleting images and folder', [
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString()
            ]);

            rabbitmq()->sendMessage($message, 'logs');
        }

        return false;
    }

    /**
     * Updates main image by entity type and entity id
     *
     * @param Image $image
     * @return bool
     */
    public function updateMainImage(Image $image)
    {
        Image::modelImages($image->model_type, $image->model_id)->update(['is_main' => false]);

        $message = new LogMessageDto('images', 'notice', 'Updated main image for model', [
            'model id' => $image->model_id,
            'model type' => $image->model_type
        ]);

        rabbitmq()->sendMessage($message, 'logs');

        return $image->update([
            'is_main' => true
        ]);
    }

    /**
     * Delete one image from CDN
     *
     * @param Image $image
     * @return bool
     */
    public function deleteImage(Image $image): bool
    {
        try {
            $message = new LogMessageDto('images', 'warning', 'Deleted image from model', [
                'model id' => $image->model_id,
                'model type' => $image->model_type
            ]);

            rabbitmq()->sendMessage($message, 'logs');

            if ($image->is_main) {
                $next_image = Image::where('model_id', $image->model_id)
                    ->where('is_main', '!=', Image::IS_MAIN)
                    ->first();

                if ($next_image) {
                    $next_image->update(['is_main' => Image::IS_MAIN]);
                }
            }

            return $this->api->deleteResources($image->alias) && $image->delete();
        } catch (ApiError $exception) {
            $message = new LogMessageDto('images', 'warning', 'CDN exception while deleting image', [
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString()
            ]);

            rabbitmq()->sendMessage($message, 'logs');
        } catch (\Exception $exception) {
            $message = new LogMessageDto('images', 'warning', 'Exception while deleting image', [
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString()
            ]);

            rabbitmq()->sendMessage($message, 'logs');
        }

        return false;
    }

    /**
     * Check if current image is first in list than it will be the main image
     *
     * @param $number
     * @return bool
     */
    private function isMainImage($number): bool
    {
        return $number === 0;
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
