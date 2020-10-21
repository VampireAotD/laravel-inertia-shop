<?php

namespace App\Observers\Admin\Categories;

use App\Models\Category;
use App\Observers\Traits\SetSlug;

class CategoryObserver
{
    use SetSlug;

    /**
     * Handle the category "created" event.
     *
     * @param  \App\Models\Category $category
     * @return void
     */
    public function created(Category $category)
    {
        \Log::channel('categories')->info('New category was created by user', [
            'category name' => $category->name,
            'user' => request()->user()->name ?? 'migrations'
        ]);
    }

    /**
     * Handle the category "creating" event.
     *
     * @param Category $category
     */
    public function creating(Category $category)
    {
        $this->setSlug($category);
    }

    /**
     * Handle the category "updated" event.
     *
     * @param  \App\Models\Category $category
     * @return void
     */
    public function updated(Category $category)
    {
        \Log::channel('categories')->info('Category was updated by user', [
            'category name' => $category->name,
            'user' => request()->user()->name ?? 'migrations'
        ]);
    }

    /**
     * Handle the category "updating" event.
     *
     * @param Category $category
     */
    public function updating(Category $category)
    {
        $this->setSlug($category);
    }

    /**
     * Handle the category "deleted" event.
     *
     * @param  \App\Models\Category $category
     * @return void
     */
    public function deleted(Category $category)
    {
        \Log::channel('categories')->warning('Category was deleted by user', [
            'category name' => $category->name,
            'user' => request()->user()->name ?? 'migrations'
        ]);
    }

    /**
     * Handle the category "restored" event.
     *
     * @param  \App\Models\Category $category
     * @return void
     */
    public function restored(Category $category)
    {
        //
    }

    /**
     * Handle the category "force deleted" event.
     *
     * @param  \App\Models\Category $category
     * @return void
     */
    public function forceDeleted(Category $category)
    {
        //
    }
}
