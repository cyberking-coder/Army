<?php

namespace App\View\Composers;

use App\Models\Page;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class MenuComposer
{
    public function compose(View $view): void
    {
        $menu = Cache::remember('public.menu', 300, fn () =>
            Page::whereNull('parent_id')
                ->where('status', 'publish')
                ->with(['children' => fn ($q) => $q->where('status', 'publish')->orderBy('sort')])
                ->orderBy('sort')
                ->get()
        );

        $view->with([
            'menu'       => $menu,
            'siteTitle'  => Setting::get('site_title', 'Territorial Army'),
            'siteTagline'=> Setting::get('site_tagline', ''),
        ]);
    }
}
