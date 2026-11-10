<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Services\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(protected Cart $cart) {}

    public function index()
    {
        return view('public.cart.index', [
            'items'    => $this->cart->items(),
            'subtotal' => $this->cart->subtotal(),
        ]);
    }

    public function add(Request $request, Book $book)
    {
        abort_unless($book->is_active, 404);
        $qty = max(1, (int) $request->integer('quantity', 1));
        $this->cart->add($book, $qty);

        return redirect()->route('cart.index')
            ->with('success', "Added \"{$book->title}\" to cart.");
    }

    public function update(Request $request, Book $book)
    {
        $this->cart->update($book->id, (int) $request->integer('quantity', 1));
        return redirect()->route('cart.index');
    }

    public function remove(Book $book)
    {
        $this->cart->remove($book->id);
        return redirect()->route('cart.index');
    }
}
