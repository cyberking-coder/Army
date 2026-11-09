<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('public.home');
})->name('home');

// Legacy redirect handler (Phase 7 migration step uses this)
Route::fallback(function () {
    $path = '/' . ltrim(request()->path(), '/');
    $redirect = \App\Models\Redirect::where('from_path', $path)->first();
    if ($redirect) {
        return redirect($redirect->to_path, $redirect->status_code);
    }
    abort(404);
});
