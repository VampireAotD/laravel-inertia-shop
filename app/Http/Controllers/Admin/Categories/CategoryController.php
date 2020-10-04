<?php

namespace App\Http\Controllers\Admin\Categories;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
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
    private $repository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->repository = $categoryRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $categories = $this->repository->getItemsWithPagination();
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
        $categories = $this->repository->searchWithPagination($request);
        $perPage = (int)$request->input('perPage');

        return Inertia::render('Admin/Categories/Index', compact('categories', 'perPage'));
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
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request, Category $category)
    {
        if ($category->fill($request->input())->save()) {
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
        $category = $this->repository->getCategoryBySlugWithRelations($slug, ['products:name,slug']);
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
        $category = $this->repository->findItemBySlug($slug);
        return Inertia::render('Admin/Categories/Edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CategoryRequest $request
     * @param string $slug
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, string $slug)
    {
        /**
         * @var $category Category
         */
        $category = $this->repository->findItemBySlug($slug);

        if ($category->update($request->input())) {
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
     * @return \Illuminate\Http\Response
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
