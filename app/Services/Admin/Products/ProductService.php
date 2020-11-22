<?php

namespace App\Services\Admin\Products;

use App\DTO\RabbitMq\LogMessageDto;
use App\Http\Requests\Products\ProductRequest;
use App\Http\Requests\Products\UpdateProductRequest;
use App\Models\Product;
use App\Services\Admin\Images\ImageService;
use Illuminate\Http\UploadedFile;

class ProductService
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
     * Creates a new instance of Product or update if exist
     *
     * Return true and commits the transaction if it successfully created or updated
     *
     * @param Product $product
     * @param ProductRequest|UpdateProductRequest $request
     * @return bool
     */
    public function upsert(Product $product, $request)
    {
        try {
            \DB::beginTransaction();
            if ($product->fill($request->input())->save()) {
                if ($product->categories()->sync($request->input('categories'))) {
                    foreach ($request->images as $key => $image) {
                        if ($image instanceof UploadedFile) {
                            $product->images()->save(
                                $this->imageService->createImage(
                                    $product,
                                    Product::class,
                                    Product::PRODUCTS_FOLDER,
                                    $image,
                                    $key
                                )
                            );
                        }
                    }
                    \DB::commit();

                    elasticsearch()->addDocumentToIndex('products', $product);

                    return true;
                }
            }
        } catch (\Throwable $exception) {
            $message = new LogMessageDto('products', 'warning', 'CDN exception while upserting product', [
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'message' => $exception->getMessage()
            ]);

            rabbitmq()->sendMessage($message, 'logs');

            \DB::rollBack();
        }

        return false;
    }
}
