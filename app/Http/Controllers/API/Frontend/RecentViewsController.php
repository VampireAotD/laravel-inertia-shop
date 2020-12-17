<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class RecentViewsController extends Controller
{
    public function __invoke(Request $request)
    {
        if ($request->ajax()) {
            if (Redis::hExists('users', 'user:' . $request->ip())) {
                $list = json_decode(Redis::hGet('users', 'user:' . $request->ip()), true);

                usort($list, fn($current, $next) => $current['expire'] < $next['expire']);

                return Product::find(collect($list)->pluck('id'));
            }

            return [];
        }

        return redirect()->route('home');
    }
}
