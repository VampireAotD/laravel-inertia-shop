<?php

namespace App\Http\Controllers\Admin\Images;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\Images\ImageRepositoryInterface;
use App\Services\Admin\Interfaces\Images\ImageServiceInterface;

class ImageController extends Controller
{
    private $repository;

    private $service;

    public function __construct(ImageRepositoryInterface $imageRepository, ImageServiceInterface $imageService)
    {
        $this->repository = $imageRepository;
        $this->service = $imageService;
    }

    /**
     * Update main image for one entity
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateImage(int $id)
    {
        $image = $this->repository->findItemById($id);

        if ($this->service->updateMainImage($image)) {
            return back();
        }

        return redirect()
            ->route('admin.products.index')
            ->withErrors([
                'error' => 'Error while updating main image'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroyImage($id)
    {
        $image = $this->repository->findItemById($id);

        if ($this->service->deleteImage($image)) {
            return back();
        }

        return redirect()
            ->route('admin.products.index')
            ->withErrors([
                'error' => 'Error while deleting image'
            ]);
    }
}
