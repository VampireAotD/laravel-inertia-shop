<?php

namespace App\Repositories\Admin\Categories;

use App\Repositories\Interfaces\FindItemById;
use App\Repositories\Interfaces\FindItemBySlug;
use App\Repositories\Interfaces\GetItemsCollectionWithPagination;

interface CategoryRepositoryInterface extends FindItemById, FindItemBySlug, GetItemsCollectionWithPagination
{

}