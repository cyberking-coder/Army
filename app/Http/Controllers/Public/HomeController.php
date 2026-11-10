<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Newsline;
use App\Models\Pdf;
use App\Models\Review;
use App\Models\Slider;

class HomeController extends Controller
{
    public function index()
    {
        return view('public.home', [
            'sliders'       => Slider::active()->get(),
            'newslines'     => Newsline::where('is_active', true)->orderBy('sort')->take(10)->get(),
            'books'         => Book::where('is_active', true)->latest()->take(4)->get(),
            'featuredBook'  => Book::where('is_active', true)->whereNotNull('cover_image_path')
                                   ->orderByRaw('COALESCE(discount_price, price) DESC')->first()
                                   ?? Book::where('is_active', true)->first(),
            'reviews'       => Review::where('is_published', true)->latest()->take(3)->get(),
            'recentPdfs'    => Pdf::where('is_active', true)->latest()->take(6)->get(),
        ]);
    }
}
