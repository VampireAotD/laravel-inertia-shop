<?php

namespace App\Http\Controllers\Admin\Images;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\Images\ImageRepositoryInterface;
use App\Services\Admin\Images\ImageService;

class ImageController extends Controller
{
    /**
     * @var ImageRepositoryInterface
     */
    private $imageRepository;

    /**
     * @var ImageService
     */
    private $imageService;

    public function __construct(
        ImageRepositoryInterface $imageRepository,
        ImageService $imageService
    )
    {
        $this->imageRepository = $imageRepository;
        $this->imageService = $imageService;
    }

    /**
     * Update main image for one entity
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateImage(int $id)
    {
        $image = $this->imageRepository->findItemById($id);

        if ($this->imageService->updateMainImage($image)) {
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
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyImage(int $id)
    {
        $image = $this->imageRepository->findItemById($id);

        if ($this->imageService->deleteImage($image)) {
            return back();
        }

        return redirect()
            ->route('admin.products.index')
            ->withErrors([
                'error' => 'Error while deleting image'
            ]);
    }
}
