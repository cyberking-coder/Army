<?php

namespace App\Services;

use App\Models\Book;
use Illuminate\Support\Collection;

/**
 * Session-backed shopping cart. One cart per browser session.
 * Stored shape: ['book_id' => ['quantity' => int]]
 */
class Cart
{
    protected string $key = 'cart';

    public function add(Book $book, int $qty = 1): void
    {
        $cart = session($this->key, []);
        $cart[$book->id] = ['quantity' => ($cart[$book->id]['quantity'] ?? 0) + $qty];
        session([$this->key => $cart]);
    }

    public function update(int $bookId, int $qty): void
    {
        $cart = session($this->key, []);
        if ($qty <= 0) {
            unset($cart[$bookId]);
        } else {
            $cart[$bookId] = ['quantity' => $qty];
        }
        session([$this->key => $cart]);
    }

    public function remove(int $bookId): void
    {
        $cart = session($this->key, []);
        unset($cart[$bookId]);
        session([$this->key => $cart]);
    }

    public function clear(): void
    {
        session()->forget($this->key);
    }

    /** @return Collection<int, array{book: Book, quantity: int, line_total: float}> */
    public function items(): Collection
    {
        $cart = session($this->key, []);
        if (empty($cart)) {
            return collect();
        }

        $books = Book::whereIn('id', array_keys($cart))->get()->keyBy('id');

        return collect($cart)
            ->map(function ($row, $id) use ($books) {
                $book = $books->get($id);
                if (! $book) return null;
                return [
                    'book'       => $book,
                    'quantity'   => $row['quantity'],
                    'line_total' => $book->effective_price * $row['quantity'],
                ];
            })
            ->filter()
            ->values();
    }

    public function count(): int
    {
        return array_sum(array_column(session($this->key, []), 'quantity'));
    }

    public function subtotal(): float
    {
        return (float) $this->items()->sum('line_total');
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }
}
