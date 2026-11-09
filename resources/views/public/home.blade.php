@extends('layouts.public')

@section('content')

{{-- HERO SLIDER --}}
@if($sliders->isNotEmpty())
<section x-data="{active:0, total:{{ $sliders->count() }}}" x-init="setInterval(()=>active=(active+1)%total,5000)" class="relative">
    @foreach($sliders as $i => $slider)
        <div x-show="active==={{ $i }}" x-transition.opacity.duration.500ms class="relative h-[55vh] min-h-[380px] bg-gray-900 overflow-hidden">
            <img src="{{ \Storage::url($slider->image_path) }}" alt="{{ $slider->title }}" class="absolute inset-0 w-full h-full object-cover opacity-70">
            <div class="relative z-10 max-w-6xl mx-auto px-4 h-full flex items-center">
                <div class="text-white max-w-2xl">
                    @if($slider->title)<h1 class="font-display font-bold text-3xl sm:text-5xl leading-tight mb-3">{{ $slider->title }}</h1>@endif
                    @if($slider->caption)<p class="text-lg text-gray-200 mb-6">{{ $slider->caption }}</p>@endif
                    @if($slider->link_url)
                        <a href="{{ $slider->link_url }}" class="inline-block bg-brand-700 hover:bg-brand-900 text-white font-medium px-6 py-3 rounded-lg transition">Learn more →</a>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
    @if($sliders->count() > 1)
    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-20">
        @foreach($sliders as $i => $_)
            <button @click="active={{ $i }}" :class="active==={{ $i }} ? 'bg-white' : 'bg-white/40'" class="w-2.5 h-2.5 rounded-full transition"></button>
        @endforeach
    </div>
    @endif
</section>
@else
<section class="bg-brand-700 text-white py-20 text-center">
    <div class="max-w-4xl mx-auto px-4">
        <h1 class="font-display font-bold text-4xl sm:text-6xl mb-4">{{ $siteTitle }}</h1>
        <p class="text-xl text-brand-100">{{ $siteTagline }}</p>
    </div>
</section>
@endif

{{-- NEWSLINE TICKER --}}
@if($newslines->isNotEmpty())
<section class="bg-amber-100 border-y border-amber-200 py-2 overflow-hidden">
    <div class="max-w-6xl mx-auto px-4 flex items-center gap-4 text-sm">
        <span class="bg-amber-500 text-white font-semibold px-3 py-1 rounded shrink-0">📢 News</span>
        <div class="flex gap-10 whitespace-nowrap animate-marquee">
            @foreach($newslines as $n)
                <span class="text-amber-900">{{ $n->headline }}</span>
            @endforeach
        </div>
    </div>
    <style>
    @keyframes marquee { from { transform: translateX(0); } to { transform: translateX(-50%); } }
    .animate-marquee { animation: marquee 30s linear infinite; }
    </style>
</section>
@endif

{{-- QUICK LINKS --}}
<section class="max-w-6xl mx-auto px-4 py-12">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach([
            ['Join as Jawan','join-as-jawan','🪖'],
            ['Join as Officer','join-as-officer','🎖'],
            ['Download PDFs',route('pdfs.index'),'📂'],
            ['Register for Exam',route('exams.index'),'📝'],
        ] as [$label,$url,$icon])
            <a href="{{ str_starts_with($url, 'http') || str_starts_with($url, '/') ? $url : '/'.$url }}"
               class="bg-brand-50 hover:bg-brand-100 border border-brand-100 rounded-xl p-6 text-center transition">
                <div class="text-4xl mb-2">{{ $icon }}</div>
                <div class="font-semibold text-brand-900">{{ $label }}</div>
            </a>
        @endforeach
    </div>
</section>

{{-- LATEST BOOKS --}}
@if($books->isNotEmpty())
<section class="max-w-6xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <h2 class="font-display font-bold text-2xl">Latest Books</h2>
        <a href="{{ route('books.index') }}" class="text-brand-700 hover:underline text-sm">View all →</a>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($books as $book)
            <a href="{{ route('books.show', $book->slug) }}" class="group">
                <div class="aspect-[3/4] bg-gray-100 rounded-lg overflow-hidden mb-2">
                    @if($book->cover_image_path)
                        <img src="{{ \Storage::url($book->cover_image_path) }}" alt="{{ $book->title }}" class="w-full h-full object-cover group-hover:scale-105 transition">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-4xl text-gray-300">📘</div>
                    @endif
                </div>
                <div class="font-medium text-sm line-clamp-2 group-hover:text-brand-700">{{ $book->title }}</div>
                <div class="text-brand-700 font-bold text-sm mt-1">₹{{ number_format($book->effective_price) }}</div>
            </a>
        @endforeach
    </div>
</section>
@endif

{{-- REVIEWS --}}
@if($reviews->isNotEmpty())
<section class="bg-gray-50 py-12 mt-12">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="font-display font-bold text-2xl text-center mb-8">What our subscribers say</h2>
        <div class="grid md:grid-cols-3 gap-6">
            @foreach($reviews as $review)
                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <div class="text-amber-500 mb-2">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5-$review->rating) }}</div>
                    <p class="text-gray-700 mb-3">"{{ $review->comment }}"</p>
                    <div class="font-semibold text-sm">— {{ $review->name }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- CTA --}}
<section class="bg-brand-900 text-white py-16 text-center">
    <div class="max-w-3xl mx-auto px-4">
        <h2 class="font-display font-bold text-3xl mb-3">Subscribe for exam alerts</h2>
        <p class="text-brand-100 mb-6">Get notified about new exam dates, syllabus changes and study material.</p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('subscribe.officer') }}" class="bg-white text-brand-900 font-semibold px-6 py-3 rounded-lg hover:bg-brand-50 transition">Subscribe as Officer</a>
            <a href="{{ route('subscribe.soldier') }}" class="bg-brand-700 text-white font-semibold px-6 py-3 rounded-lg hover:bg-brand-500 transition border border-white">Subscribe as Soldier</a>
        </div>
    </div>
</section>

@endsection
