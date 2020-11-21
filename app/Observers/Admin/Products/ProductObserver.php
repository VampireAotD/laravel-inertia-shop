<?php

namespace App\Observers\Admin\Products;

use App\DTO\RabbitMq\LogMessageDto;
use App\Models\Product;
use App\Observers\Traits\SetSlug;
use App\Services\Admin\Images\ImageService;

class ProductObserver
{
    use SetSlug;

    /**
     * @var ImageService
     */
    private $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Handle the product "creating" event.
     *
     * @param Product $product
     */
    public function creating(Product $product)
    {
        $this->setSlug($product);
    }

    /**
     * Handle the product "created" event.
     *
     * @param  \App\Models\Product $product
     * @return void
     */
    public function created(Product $product)
    {
        $message = new LogMessageDto('products', 'info','New product was created by user', [
            'category name' => $product->name,
            'user' => request()->user()->name ?? 'migrations'
        ]);

        rabbitmq()->sendMessage($message, 'logs');
    }

    /**
     * Handle the product "creating" event.
     *
     * @param Product $product
     */
    public function updating(Product $product)
    {
        $this->setSlug($product);
    }

    /**
     * Handle the product "updated" event.
     *
     * @param  \App\Models\Product $product
     * @return void
     */
    public function updated(Product $product)
    {
        $message = new LogMessageDto('products', 'notice','Product was updated by user', [
            'category name' => $product->name,
            'user' => request()->user()->name ?? 'migrations'
        ]);

        rabbitmq()->sendMessage($message, 'logs');
    }

    /**
     * Handle the product "deleted" event.
     *
     * @param  \App\Models\Product $product
     * @return void
     */
    public function deleted(Product $product)
    {
        if ($this->imageService->deleteImagesWithFolderFromCDN($product, "products/$product->slug", Product::class)) {
            $this->imageService->deleteImagesFromDB($product);
        }

        elasticsearch()->deleteDocumentFromIndex('products', $product);

        $message = new LogMessageDto('products', 'warning','Product was deleted by user', [
            'category name' => $product->name,
            'user' => request()->user()->name ?? 'migrations'
        ]);

        rabbitmq()->sendMessage($message, 'logs');
    }

    /**
     * Handle the product "restored" event.
     *
     * @param  \App\Models\Product $product
     * @return void
     */
    public function restored(Product $product)
    {
        //
    }

    /**
     * Handle the product "force deleted" event.
     *
     * @param  \App\Models\Product $product
     * @return void
     */
    public function forceDeleted(Product $product)
    {
        //
    }
}
