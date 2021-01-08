<?php

namespace App\Providers;

use Cache;
use File;
use Illuminate\Support\ServiceProvider;

class TranslationServiceProdiver extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Cache::rememberForever('translations', function () {
            $translations = collect();

            $languageFolder = glob(resource_path('lang/*'));

            $locales = array_map(fn($dir) => basename($dir), $languageFolder);

            foreach ($locales as $locale) {
                $translations[$locale] = $this->translations($locale);
            }

            return $translations;
        });
    }

    /**
     * Collection of translations for chosen locale
     *
     * @param $locale
     * @return \Illuminate\Support\Collection
     */
    protected function translations($locale)
    {
        $path = resource_path("lang/$locale");

        return collect(File::allFiles($path))->flatMap(function ($file) use ($locale) {
            $key = ($translation = $file->getBasename('.php'));

            return [$key => trans($translation, [], $locale)];
        });
    }
}
