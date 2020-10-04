<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

interface SearchWithPagination
{
    /**
     * Search for items by request
     *
     * @param Request $request
     * @return Collection
     */
    public function searchWithPagination(Request $request);
}