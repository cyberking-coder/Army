<?php

use App\Http\Controllers\Public\BookController;
use App\Http\Controllers\Public\CartController;
use App\Http\Controllers\Public\CheckoutController;
use App\Http\Controllers\Public\ExamController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\PageController;
use App\Http\Controllers\Public\PdfController;
use App\Http\Controllers\Public\StudyMaterialController;
use App\Http\Controllers\Public\SubscriptionController;
use App\Models\Newsline;
use App\Models\Review;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{book:slug}', [BookController::class, 'show'])->name('books.show');

Route::get('/pdfs', [PdfController::class, 'index'])->name('pdfs.index');
Route::get('/pdfs/{pdf:slug}/download', [PdfController::class, 'download'])->name('pdfs.download');

Route::get('/study-material', [StudyMaterialController::class, 'index'])->name('study.index');

Route::get('/exams', [ExamController::class, 'index'])->name('exams.index');
Route::get('/exams/{exam:slug}/register', [ExamController::class, 'register'])->name('exams.register');
Route::post('/exams/{exam:slug}/register', [ExamController::class, 'submit'])->middleware('throttle:public-forms');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{book:slug}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update/{book}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{book}', [CartController::class, 'remove'])->name('cart.remove');

// Checkout + CCAvenue
Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout', [CheckoutController::class, 'place'])->name('checkout.place');

// CCAvenue POSTs here after payment with encResp (must bypass CSRF — comes from CCAvenue's domain)
Route::post('/checkout/ccavenue/return', [CheckoutController::class, 'ccavenueReturn'])
    ->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class)
    ->name('checkout.ccavenue.return');

Route::post('/checkout/{order}/dev-paid', [CheckoutController::class, 'devMarkPaid'])->name('checkout.dev_paid');
Route::get('/order/{order}', [CheckoutController::class, 'orderShow'])->name('order.show');

// Subscribers
Route::get('/subscribe/officer', [SubscriptionController::class, 'officerForm'])->name('subscribe.officer');
Route::post('/subscribe/officer', [SubscriptionController::class, 'officerStore'])->middleware('throttle:public-forms');
Route::get('/subscribe/soldier', [SubscriptionController::class, 'soldierForm'])->name('subscribe.soldier');
Route::post('/subscribe/soldier', [SubscriptionController::class, 'soldierStore'])->middleware('throttle:public-forms');
Route::get('/verify-otp/{type}/{id}', [SubscriptionController::class, 'verifyForm'])->name('subscribe.verify');
Route::post('/verify-otp/{type}/{id}', [SubscriptionController::class, 'verify']);
Route::post('/verify-otp/{type}/{id}/resend', [SubscriptionController::class, 'resend'])
    ->middleware('throttle:public-forms')->name('subscribe.resend');
Route::get('/unsubscribe/{token}', [SubscriptionController::class, 'unsubscribe'])->name('unsubscribe');

Route::get('/reviews', function () {
    return view('public.reviews', [
        'reviews' => Review::where('is_published', true)->latest()->paginate(20),
    ]);
})->name('reviews');

Route::get('/newsline', function () {
    return view('public.newsline', [
        'newslines' => Newsline::where('is_active', true)->latest()->paginate(20),
    ]);
})->name('newsline');

Route::view('/about', 'public.about')->name('about');
Route::view('/contact', 'public.contact')->name('contact');
Route::view('/privacy', 'public.privacy')->name('privacy');
Route::view('/terms', 'public.terms')->name('terms');

// Dynamic page by slug (must be last named route before fallback)
Route::get('/{slug}', [PageController::class, 'show'])
    ->where('slug', '[a-z0-9\-]+')
    ->name('page.show');

// Legacy redirect handler (Phase 7 migration)
Route::fallback(function () {
    $path = '/' . ltrim(request()->path(), '/');
    $redirect = \App\Models\Redirect::where('from_path', $path)->first();
    if ($redirect) {
        return redirect($redirect->to_path, $redirect->status_code);
    }
    abort(404);
});
