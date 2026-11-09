<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Pdf;
use Illuminate\Support\Facades\Storage;

class PdfController extends Controller
{
    public function index()
    {
        return view('public.pdfs.index', [
            'pdfs' => Pdf::where('is_active', true)->latest()->paginate(20),
        ]);
    }

    public function download(Pdf $pdf)
    {
        abort_unless($pdf->is_active, 404);
        $pdf->increment('downloads_count');

        return Storage::disk(config('filesystems.default') === 'r2' ? 'r2' : 'public')
            ->download($pdf->file_path, $pdf->slug.'.pdf');
    }
}
