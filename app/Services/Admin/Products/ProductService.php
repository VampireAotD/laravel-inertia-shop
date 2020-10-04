<?php

namespace App\Services\Admin\Products;

use App\Http\Requests\Products\ProductRequest;
use App\Http\Requests\Products\UpdateProductRequest;
use App\Models\Product;
use App\Services\Admin\Interfaces\Images\ImageServiceInterface;
use Illuminate\Http\UploadedFile;

class ProductService
{
    /**
     * @var Product
     */
    private $product;

    /**
     * @var ImageServiceInterface
     */
    private $imageService;

    public function __construct(Product $product, ImageServiceInterface $imageService)
    {
        $this->product = $product;
        $this->imageService = $imageService;
    }

    /**
     * Creates a new instance of Product
     *
     * Return true and commits the transaction if it successfully created
     *
     * @param ProductRequest $request
     * @return bool
     */
    public function store(ProductRequest $request)
    {
        try {
            \DB::beginTransaction();
            if ($this->product->fill($request->input())->save()) {
                if ($this->product->categories()->sync($request->input('categories'))) {
                    foreach ($request->images as $key => $image) {
                        $this->product->images()->save(
                            $this->imageService->createImage(
                                $this->product,
                                Product::class,
                                $key,
                                Product::PRODUCTS_FOLDER,
                                $image
                            )
                        );
                    }
                    \DB::commit();
                    return true;
                }
            }
        } catch (\Throwable $e) {
            dd($e->getFile(), $e->getMessage(), $e->getLine());
        }

        return false;
    }

    /**
     * Updates a Product instance
     *
     * Return true and commits the transaction if it successfully updated
     *
     * @param UpdateProductRequest $request
     * @param Product $product
     * @return bool
     */
    public function update(UpdateProductRequest $request, Product $product)
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
                                    $key,
                                    Product::PRODUCTS_FOLDER,
                                    $image
                                )
                            );
                        }
                    }
                    \DB::commit();
                    return true;
                }
            }
        } catch (\Throwable $e) {
            dd($e->getFile(), $e->getMessage(), $e->getLine());
        }

        return false;
    }
}