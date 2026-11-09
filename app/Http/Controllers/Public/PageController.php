<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Page;

class PageController extends Controller
{
    public function show(string $slug)
    {
        $page = Page::where('slug', $slug)->where('status', 'publish')->firstOrFail();

        return view('public.page', [
            'page' => $page,
            'children' => $page->children()->where('status', 'publish')->get(),
        ]);
    }
}
