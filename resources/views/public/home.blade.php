@extends('layouts.public')

@section('content')

{{-- HERO + FEATURED BOOK --}}
<section class="bg-gradient-to-b from-white to-army-50 border-b border-army-100">
    <div class="max-w-7xl mx-auto px-4 py-6 grid lg:grid-cols-[1fr,360px] gap-6">

        {{-- SLIDER --}}
        <div x-data="{active:0, total:{{ max($sliders->count(), 1) }}}"
             @if($sliders->count() > 1) x-init="setInterval(()=>active=(active+1)%total,5000)" @endif
             class="relative rounded-2xl overflow-hidden bg-army-900 aspect-[16/9] lg:aspect-[16/10] shadow-xl">
            @forelse($sliders as $i => $slider)
                <div x-show="active==={{ $i }}" x-transition.opacity.duration.500ms class="absolute inset-0">
                    <img src="{{ \Storage::url($slider->image_path) }}" alt="{{ $slider->title }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-army-900/80 via-army-900/20 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 p-6 md:p-10 text-white max-w-2xl">
                        @if($slider->title)<h1 class="font-display font-bold text-2xl md:text-4xl leading-tight mb-2">{{ $slider->title }}</h1>@endif
                        @if($slider->caption)<p class="text-army-100 text-sm md:text-base mb-4">{{ $slider->caption }}</p>@endif
                        @if($slider->link_url)
                            <a href="{{ $slider->link_url }}" class="inline-block bg-gold-500 hover:bg-gold-600 text-army-900 font-semibold text-sm px-5 py-2.5 rounded-lg transition">Learn more →</a>
                        @endif
                    </div>
                </div>
            @empty
                {{-- Placeholder hero --}}
                <div class="absolute inset-0 bg-gradient-to-br from-army-700 via-army-800 to-army-900 flex items-center justify-center">
                    <div class="text-center text-white px-4 max-w-2xl">
                        <div class="text-6xl mb-4">🎖️</div>
                        <h1 class="font-display font-bold text-3xl md:text-5xl mb-3">{{ $siteTitle }}</h1>
                        <p class="text-army-100 text-lg mb-6">{{ $siteTagline }}</p>
                        <a href="/join-as-officer" class="inline-block bg-gold-500 hover:bg-gold-600 text-army-900 font-semibold px-6 py-3 rounded-lg transition">Join Us →</a>
                    </div>
                </div>
            @endforelse

            @if($sliders->count() > 1)
            <div class="absolute bottom-4 right-4 flex gap-2 z-10">
                @foreach($sliders as $i => $_)
                    <button @click="active={{ $i }}" :class="active==={{ $i }} ? 'bg-white w-6' : 'bg-white/50 w-2'" class="h-2 rounded-full transition-all"></button>
                @endforeach
            </div>
            @endif
        </div>

        {{-- FEATURED BOOK CARD --}}
        @if($featuredBook)
        <aside class="bg-white rounded-2xl border border-army-100 shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-army-700 to-army-900 text-white px-5 py-3">
                <div class="text-xs uppercase tracking-wider text-army-100">Featured Book</div>
                <div class="font-display font-bold text-base leading-tight">{{ $featuredBook->title }}</div>
            </div>
            <div class="p-5">
                <div class="aspect-[3/4] bg-army-50 rounded-lg overflow-hidden mb-4">
                    @if($featuredBook->cover_image_path)
                        <img src="{{ \Storage::url($featuredBook->cover_image_path) }}" alt="" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-5xl text-army-200">📘</div>
                    @endif
                </div>
                <div class="flex items-baseline gap-2 mb-4">
                    <span class="text-2xl font-bold text-army-900">₹{{ number_format($featuredBook->effective_price) }}</span>
                    @if($featuredBook->discount_price)
                        <span class="text-gray-400 text-sm line-through">₹{{ number_format($featuredBook->price) }}</span>
                    @endif
                </div>
                <a href="{{ route('books.show', $featuredBook->slug) }}" class="block bg-gold-500 hover:bg-gold-600 text-army-900 text-center font-semibold py-2.5 rounded-lg transition mb-2">Buy Now</a>
                <a href="{{ route('reviews') }}" class="block bg-army-50 hover:bg-army-100 text-army-800 text-center text-sm py-2 rounded-lg transition">Read Reviews</a>
            </div>
        </aside>
        @endif
    </div>
</section>

{{-- NEWS TICKER --}}
@if($newslines->isNotEmpty())
<section class="bg-army-900 text-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 py-2.5 flex items-center gap-4 text-sm">
        <span class="bg-saffron text-ink font-bold px-3 py-1 rounded shrink-0 text-xs uppercase tracking-wider">● Live</span>
        <div class="flex gap-10 whitespace-nowrap animate-marquee flex-1">
            @foreach($newslines as $n)
                <span class="text-army-100">{{ $n->headline }}</span>
            @endforeach
            @foreach($newslines as $n)
                <span class="text-army-100">{{ $n->headline }}</span>
            @endforeach
        </div>
    </div>
    <style>
    @keyframes marquee { from { transform: translateX(0); } to { transform: translateX(-50%); } }
    .animate-marquee { animation: marquee 40s linear infinite; }
    .animate-marquee:hover { animation-play-state: paused; }
    </style>
</section>
@endif

{{-- IMPORTANT DOWNLOADS / ANNOUNCEMENTS --}}
@if($newslines->isNotEmpty() || $recentPdfs->isNotEmpty())
<section class="max-w-7xl mx-auto px-4 py-12">
    <div class="flex items-end justify-between mb-6">
        <div>
            <div class="text-saffron text-xs font-semibold uppercase tracking-wider mb-1">Important</div>
            <h2 class="font-display font-bold text-3xl text-army-900">Downloads &amp; Announcements</h2>
        </div>
        <a href="{{ route('pdfs.index') }}" class="hidden sm:inline text-army-700 hover:underline text-sm font-medium">View all →</a>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
        {{-- Announcements from newsline --}}
        @foreach($newslines->take(3) as $n)
            <a href="{{ $n->link_url ?? route('newsline') }}" class="bg-army-50 hover:bg-army-100 border-l-4 border-saffron rounded-r-lg p-4 transition flex items-start gap-3">
                <span class="bg-saffron text-white text-[10px] font-bold px-2 py-0.5 rounded shrink-0 mt-0.5">NEW</span>
                <div class="text-sm text-army-900 font-medium leading-relaxed line-clamp-3">{{ $n->headline }}</div>
            </a>
        @endforeach

        {{-- Recent PDFs --}}
        @foreach($recentPdfs->take(6) as $pdf)
            <a href="{{ route('pdfs.download', $pdf->slug) }}" class="bg-white border border-army-100 hover:border-army-500 hover:shadow-md rounded-lg p-4 transition flex items-start gap-3 group">
                <div class="w-10 h-10 bg-army-100 text-army-700 rounded-lg flex items-center justify-center shrink-0 group-hover:bg-army-700 group-hover:text-white transition">
                    📄
                </div>
                <div class="min-w-0">
                    <div class="text-sm font-semibold text-army-900 line-clamp-2 group-hover:text-army-700">{{ $pdf->title }}</div>
                    @if($pdf->category)<div class="text-xs text-army-600 mt-1">{{ $pdf->category }}</div>@endif
                </div>
                <svg class="w-4 h-4 text-army-300 group-hover:text-army-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        @endforeach
    </div>
</section>
@endif

{{-- HISTORY & INTRODUCTION --}}
<section class="bg-army-50/40 border-y border-army-100 py-16">
    <div class="max-w-5xl mx-auto px-4">
        <div class="text-center mb-10">
            <div class="text-saffron text-xs font-semibold uppercase tracking-wider mb-2">Legacy</div>
            <h2 class="font-display font-bold text-4xl text-army-900">History &amp; Introduction</h2>
            <div class="w-24 h-1 bg-gold-500 mx-auto mt-4"></div>
        </div>

        <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
            <p class="first-letter:text-5xl first-letter:font-display first-letter:font-bold first-letter:float-left first-letter:mr-3 first-letter:text-army-700 first-letter:leading-none">
                The genesis of the Territorial Army in India can be traced back to the first war of Independence in 1857, when a Volunteer Force was raised. In 1917, the Indian Defence Force Act was enacted, under which all universities were asked to provide contingents to the Defence Force.
            </p>

            <p>Netaji Subhash Chandra Bose joined the Calcutta University Corps of Indian Defence Force in 1918. Training with the University Corps helped him later in leading the Indian National Army in the struggle for freedom. Pt. Jawaharlal Nehru was part of the Allahabad University Corps.</p>

            <p>After Independence, the Territorial Army Act was passed in 1948. The Territorial Army was formally inaugurated by the first Indian Governor General Shri C. Rajagopalachari on <strong>09 October 1949</strong> — celebrated as TA Day every year.</p>

            <p>Presently the Territorial Army has a strength of approximately <strong>fifty thousand personnel</strong> comprising 65 Departmental TA units such as Railway, IOC, ONGC, and Non-Departmental TA units of Infantry Battalions including Home &amp; Hearth Battalions, Ecological Battalions, and Composite Eco Task Forces.</p>
        </div>
    </div>
</section>

{{-- EVOLUTION TIMELINE --}}
<section class="max-w-5xl mx-auto px-4 py-16">
    <div class="text-center mb-12">
        <div class="text-saffron text-xs font-semibold uppercase tracking-wider mb-2">Journey</div>
        <h2 class="font-display font-bold text-4xl text-army-900">Evolution of TA in the Indian Context</h2>
        <div class="w-24 h-1 bg-gold-500 mx-auto mt-4"></div>
    </div>

    <div class="relative">
        {{-- vertical line --}}
        <div class="absolute left-4 md:left-1/2 top-0 bottom-0 w-0.5 bg-army-200"></div>

        @foreach([
            ['1857', 'Raised as "Volunteers" recruited only from Europeans &amp; Anglo-Indians.'],
            ['1920', 'Indian Territorial Force Bill passed; reorganization into Auxiliary Force and Indian Territorial Force.'],
            ['1948', 'Territorial Army Act enacted by the Constituent Assembly, constituting the present Territorial Army.'],
            ['1949', 'Territorial Army formally inaugurated on 9 October — celebrated as TA Day.'],
            ['1962-1971', 'TA units actively participated in 1962, 1965 and 1971 operations. Terriers also took part in OP PAWAN, OP RAKSHAK, OP RHINO and OP BAJRANG.'],
            ['1982', 'First Infantry Battalion TA Ecological raised at Dehradun.'],
            ['2001', 'Rly Engr Regt (TA) and Bridge Construction Coys converted into Inf Bns (TA).'],
            ['2015', 'Three Engr Regt (TA) raised for repairs of LC fence in 15 &amp; 16 Corps Zone.'],
            ['2018', '37 CETF (TA) and 39 GR being raised for NMCG.'],
        ] as $i => [$year, $text])
            <div class="relative flex items-start gap-4 md:gap-8 mb-8 @if($i % 2) md:flex-row-reverse @endif">
                {{-- marker --}}
                <div class="shrink-0 w-8 h-8 md:w-10 md:h-10 bg-gradient-to-br from-army-700 to-army-900 text-white rounded-full flex items-center justify-center font-bold text-xs md:text-sm shadow-lg relative z-10 md:absolute md:left-1/2 md:-translate-x-1/2">
                    {{ substr($year, -2) }}
                </div>
                {{-- card --}}
                <div class="flex-1 bg-white border border-army-100 rounded-lg p-4 md:p-5 shadow-sm hover:shadow-md transition md:max-w-[45%]">
                    <div class="text-army-700 font-display font-bold text-xl mb-1">{{ $year }}</div>
                    <p class="text-sm text-gray-700 leading-relaxed">{!! $text !!}</p>
                </div>
            </div>
        @endforeach
    </div>
</section>

{{-- LATEST BOOKS --}}
@if($books->isNotEmpty())
<section class="bg-army-50/40 border-y border-army-100 py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-end justify-between mb-8">
            <div>
                <div class="text-saffron text-xs font-semibold uppercase tracking-wider mb-1">Shop</div>
                <h2 class="font-display font-bold text-3xl text-army-900">Latest Books</h2>
            </div>
            <a href="{{ route('books.index') }}" class="text-army-700 hover:underline text-sm font-medium">View all →</a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($books as $book)
                <a href="{{ route('books.show', $book->slug) }}" class="group bg-white rounded-xl overflow-hidden border border-army-100 hover:shadow-xl transition">
                    <div class="aspect-[3/4] bg-army-50 overflow-hidden">
                        @if($book->cover_image_path)
                            <img src="{{ \Storage::url($book->cover_image_path) }}" alt="{{ $book->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-5xl text-army-200">📘</div>
                        @endif
                    </div>
                    <div class="p-3">
                        <div class="font-semibold text-sm line-clamp-2 group-hover:text-army-700">{{ $book->title }}</div>
                        <div class="text-army-800 font-bold text-base mt-1">₹{{ number_format($book->effective_price) }}</div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- REVIEWS --}}
@if($reviews->isNotEmpty())
<section class="max-w-6xl mx-auto px-4 py-16">
    <div class="text-center mb-10">
        <div class="text-saffron text-xs font-semibold uppercase tracking-wider mb-2">Testimonials</div>
        <h2 class="font-display font-bold text-3xl text-army-900">What our subscribers say</h2>
    </div>
    <div class="grid md:grid-cols-3 gap-6">
        @foreach($reviews as $review)
            <div class="bg-white border border-army-100 rounded-xl p-6 relative">
                <div class="text-gold-500 text-5xl absolute -top-2 left-4 font-display leading-none">"</div>
                <div class="text-amber-500 mb-3 mt-2">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5-$review->rating) }}</div>
                <p class="text-gray-700 mb-4 italic">"{{ $review->comment }}"</p>
                <div class="font-semibold text-sm text-army-900">— {{ $review->name }}</div>
            </div>
        @endforeach
    </div>
</section>
@endif

{{-- CTA STRIP --}}
<section class="bg-gradient-to-r from-army-800 via-army-700 to-army-800 text-white py-16 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 20% 50%, white 1px, transparent 1px), radial-gradient(circle at 80% 50%, white 1px, transparent 1px); background-size: 40px 40px;"></div>
    <div class="relative max-w-4xl mx-auto px-4 text-center">
        <h2 class="font-display font-bold text-3xl md:text-4xl mb-3">Serve the Nation. Part-time.</h2>
        <p class="text-army-100 text-lg mb-8 max-w-2xl mx-auto">Subscribe for OTP-verified updates on exam dates, syllabus changes and study material — straight to your inbox.</p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('subscribe.officer') }}" class="bg-gold-500 hover:bg-gold-600 text-army-900 font-semibold px-7 py-3 rounded-lg transition">Subscribe as Officer</a>
            <a href="{{ route('subscribe.soldier') }}" class="bg-white/10 hover:bg-white/20 text-white font-semibold px-7 py-3 rounded-lg transition border border-white/30">Subscribe as Soldier</a>
        </div>
    </div>
</section>

@endsection
