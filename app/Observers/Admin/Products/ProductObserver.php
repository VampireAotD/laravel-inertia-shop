<?php

namespace App\Observers\Admin\Products;

use App\Models\Product;
use App\Observers\Traits\SetSlug;
use App\Services\Admin\Interfaces\Images\ImageServiceInterface;

class ProductObserver
{
    use SetSlug;

    /**
     * @var ImageServiceInterface
     */
    private $imageService;

    public function __construct(ImageServiceInterface $imageService)
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
        //
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

    }

    /**
     * Handle the product "deleted" event.
     *
     * @param  \App\Models\Product $product
     * @return void
     */
    public function deleted(Product $product)
    {
        if ($this->imageService->deleteImagesWithFolderFromCDN($product, "products/$product->slug",Product::class)) {
            $this->imageService->deleteImagesFromDB($product);
        }
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
