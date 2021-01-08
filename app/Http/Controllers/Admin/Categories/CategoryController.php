<?php

namespace App\Http\Controllers\Admin\Categories;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Models\Category;
use App\Repositories\Admin\Categories\CategoryRepositoryInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    /**
     * Main repository for this controller
     *
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $categories = $this->categoryRepository->getItemsWithPagination();

        return Inertia::render('Admin/Categories/Index', compact('categories'));
    }

    /**
     * Display a listing of resources with pagination and filtered by request
     *
     * @param Request $request
     * @return \Inertia\Response
     */
    public function search(Request $request)
    {
        $categories = $this->categoryRepository->searchWithPagination($request);

        $name = (string)$request->input('name');
        $perPage = (int)$request->input('perPage');

        return Inertia::render('Admin/Categories/Index', compact('categories', 'name', 'perPage'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Category $category
     * @return \Inertia\Response
     */
    public function create(Category $category)
    {
        return Inertia::render('Admin/Categories/Create', compact('category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CategoryRequest $request
     * @param Category $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CategoryRequest $request, Category $category)
    {
        if ($category->fill($request->validated())->save()) {
            return redirect()
                ->route('admin.categories.index')
                ->with('messages', [
                    'success' => 'Successfully added new category!'
                ]);
        }

        return back()
            ->withErrors([
                'error' => 'Error while creating new category'
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param string $slug
     * @return \Inertia\Response
     */
    public function show(string $slug)
    {
        $category = $this->categoryRepository->getCategoryBySlugWithRelations($slug, ['products:name,slug']);

        return Inertia::render('Admin/Categories/Show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $slug
     * @return \Inertia\Response
     */
    public function edit(string $slug)
    {
        $category = $this->categoryRepository->findItemBySlug($slug);

        return Inertia::render('Admin/Categories/Edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCategoryRequest $request
     * @param string $slug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateCategoryRequest $request, string $slug)
    {
        /**
         * @var $category Category
         */
        $category = $this->categoryRepository->findItemBySlug($slug);

        if ($category->update($request->validated())) {
            return redirect()
                ->route('admin.categories.index')
                ->with('messages', [
                    'success' => 'Category was successfully updated!'
                ]);
        }

        return back()
            ->withErrors([
                'error' => 'Error while updating category'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category $category
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Category $category)
    {
        if ($category->delete()) {
            return redirect()
                ->route('admin.categories.index')
                ->with('messages', [
                    'success' => 'Category was successfully deleted!'
                ]);
        }

        return back()
            ->withErrors([
                'error' => 'Error while deleting category'
            ]);
    }
}
