<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Book;

class BookController extends Controller
{
    public function index()
    {
        return view('public.books.index', [
            'books' => Book::where('is_active', true)->latest()->paginate(12),
        ]);
    }

    public function show(Book $book)
    {
        abort_unless($book->is_active, 404);
        return view('public.books.show', compact('book'));
    }
}
