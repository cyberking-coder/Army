@extends('layouts.public')
@section('title', 'PDFs')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">
    <h1 class="font-display font-bold text-3xl mb-8">PDF Library</h1>

    @if($pdfs->isEmpty())
        <p class="text-gray-500">No PDFs uploaded yet.</p>
    @else
        <div class="space-y-3">
            @foreach($pdfs as $pdf)
                <div class="flex items-center justify-between bg-white border rounded-lg p-4 hover:border-brand-500 transition">
                    <div class="flex items-center gap-4 min-w-0">
                        <div class="text-3xl shrink-0">📄</div>
                        <div class="min-w-0">
                            <div class="font-semibold truncate">{{ $pdf->title }}</div>
                            @if($pdf->description)<div class="text-sm text-gray-500 line-clamp-1">{{ $pdf->description }}</div>@endif
                            <div class="text-xs text-gray-400 mt-1">
                                @if($pdf->category)<span class="bg-brand-50 text-brand-700 px-2 py-0.5 rounded">{{ $pdf->category }}</span>@endif
                                @if($pdf->file_size_kb) · {{ round($pdf->file_size_kb/1024,1) }} MB @endif
                                @if($pdf->downloads_count) · {{ $pdf->downloads_count }} downloads @endif
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('pdfs.download', $pdf->slug) }}"
                       class="shrink-0 bg-brand-700 hover:bg-brand-900 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                        Download
                    </a>
                </div>
            @endforeach
        </div>
        <div class="mt-8">{{ $pdfs->links() }}</div>
    @endif
</div>
@endsection
