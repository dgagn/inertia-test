<?php

declare(strict_types=1);

namespace App\View\Components;

use App;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;

class Translations extends Component
{
    private function cacheKey(): string
    {
        $locale = App::getLocale();
        return "translations.$locale";
    }

    private function cache(Closure $callback)
    {
        if (App::isProduction()) {
            return \Cache::rememberForever($this->cacheKey(), $callback);
        }

        return $callback();
    }

    public function render(): View
    {
        $translations = $this->cache(function () {
            $locale = App::getLocale();

            $t = collect(Arr::dot([
                'auth' => trans('auth'),
                'passwords' => trans('passwords')
            ]));

            $resource = App::langPath("$locale.json");
            if (\File::exists($resource)) {
                $json = json_decode(\File::get($resource, true));
            }

            return $t->merge($json ?? []);
        });

        return view('components.translations', compact('translations'));
    }
}
