<?php

namespace App\Http\Controllers\Frontend\Language;

use App\Http\Controllers\Controller;

class LanguageController extends Controller
{
    public function __invoke(string $lang)
    {
        $locales = array_map(fn($dir) => basename($dir), glob(resource_path('lang/*')));

        if (in_array($lang, $locales)) {
            cache()->set('language', $lang, 3600);
        }

        return back();
    }
}
