<!doctype html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', $siteTitle)</title>
    <meta name="description" content="@yield('description', $siteTagline)">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            fontFamily: {
              sans: ['Inter','system-ui','sans-serif'],
              display: ['"Playfair Display"','Georgia','serif'],
            },
            colors: {
              army: {
                50: '#F4F6F2', 100: '#E4E9DB', 200: '#C3CCA8',
                500: '#6B7E4A', 600: '#5C6E3D', 700: '#4A5A30',
                800: '#38461F', 900: '#283312',
              },
              gold:  { 500: '#C9A227', 600: '#A98619' },
              saffron: '#FF9933', ink: '#1A1F13',
            }
          }
        }
      }
    </script>
    <style>[x-cloak]{display:none!important}</style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
</head>
<body class="font-sans text-ink bg-white antialiased">

{{-- TOP BAR --}}
<div class="bg-army-900 text-army-100 text-xs">
    <div class="max-w-7xl mx-auto px-4 py-1.5 flex items-center justify-between">
        <div>{{ \App\Models\Setting::get('contact_phone') }} · {{ \App\Models\Setting::get('contact_email') }}</div>
        <div class="hidden sm:flex items-center gap-3">
            <a href="#" class="hover:text-white">Facebook</a>
            <span>·</span>
            <a href="#" class="hover:text-white">Twitter / X</a>
            <span>·</span>
            <a href="{{ route('subscribe.officer') }}" class="hover:text-white">Subscribe</a>
        </div>
    </div>
</div>

{{-- MAIN HEADER --}}
<header class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between gap-6">
        <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0">
            <div class="w-11 h-11 bg-gradient-to-br from-army-700 to-army-900 rounded-full flex items-center justify-center text-white font-display font-bold text-xl">TA</div>
            <div class="leading-tight">
                <div class="font-display font-bold text-lg text-army-900">{{ $siteTitle }}</div>
                <div class="text-xs text-army-700 tracking-wide">सावधानी व शूरता</div>
            </div>
        </a>

        <nav class="hidden lg:flex items-center gap-7 text-sm font-medium">
            <a href="{{ route('home') }}" class="hover:text-army-700">Home</a>
            @foreach($menu->take(5) as $item)
                @if($item->children->isEmpty())
                    <a href="{{ route('page.show', $item->slug) }}" class="hover:text-army-700">{{ $item->menu_name }}</a>
                @else
                    <div class="relative group">
                        <button class="flex items-center gap-1 hover:text-army-700">
                            {{ $item->menu_name }}
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="absolute left-0 top-full w-56 bg-white border border-gray-100 shadow-xl py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition">
                            @foreach($item->children as $child)
                                <a href="{{ route('page.show', $child->slug) }}" class="block px-4 py-2 text-sm hover:bg-army-50 hover:text-army-700">{{ $child->menu_name }}</a>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
            <a href="{{ route('pdfs.index') }}" class="hover:text-army-700">Downloads</a>
            <a href="{{ route('contact') }}" class="hover:text-army-700">Contact</a>
        </nav>

        <div x-data="{open:false}" class="lg:hidden">
            <button @click="open=!open" class="p-2 text-army-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div x-show="open" x-cloak @click.outside="open=false"
                 class="absolute top-full right-0 mt-0 w-72 bg-white border-l border-b shadow-xl py-2 text-sm">
                <a href="{{ route('home') }}" class="block px-4 py-2.5 hover:bg-army-50">Home</a>
                @foreach($menu as $item)
                    <a href="{{ route('page.show', $item->slug) }}" class="block px-4 py-2.5 hover:bg-army-50">{{ $item->menu_name }}</a>
                @endforeach
                <a href="{{ route('books.index') }}" class="block px-4 py-2.5 hover:bg-army-50">Books</a>
                <a href="{{ route('pdfs.index') }}" class="block px-4 py-2.5 hover:bg-army-50">PDFs</a>
                <a href="{{ route('exams.index') }}" class="block px-4 py-2.5 hover:bg-army-50">Exams</a>
                <a href="{{ route('subscribe.officer') }}" class="block px-4 py-2.5 hover:bg-army-50">Subscribe</a>
                <a href="{{ route('contact') }}" class="block px-4 py-2.5 hover:bg-army-50">Contact</a>
            </div>
        </div>
    </div>

    {{-- SECONDARY RECRUITMENT NAV --}}
    <div class="bg-army-50 border-t border-army-100 hidden md:block">
        <div class="max-w-7xl mx-auto px-4 py-2 flex items-center gap-6 text-xs font-medium text-army-900 overflow-x-auto">
            <a href="/join-as-jawan" class="shrink-0 hover:text-army-700">Join as Jawan / JCO</a>
            <span class="text-army-200">│</span>
            <a href="/join-as-officer" class="shrink-0 hover:text-army-700">Join as Officer</a>
            <span class="text-army-200">│</span>
            <a href="{{ route('subscribe.officer') }}" class="shrink-0 hover:text-army-700 flex items-center gap-1">
                <span class="bg-saffron text-white text-[10px] px-1.5 py-0.5 rounded">NEW</span>
                SMS Alerts
            </a>
            <span class="text-army-200">│</span>
            <a href="{{ route('pdfs.index') }}" class="shrink-0 hover:text-army-700">Previous Question Papers</a>
            <span class="text-army-200">│</span>
            <a href="{{ route('pdfs.index') }}" class="shrink-0 hover:text-army-700">Practice Papers</a>
            <span class="text-army-200">│</span>
            <a href="{{ route('study.index') }}" class="shrink-0 hover:text-army-700">Study Material</a>
            <span class="text-army-200">│</span>
            <a href="{{ route('exams.index') }}" class="shrink-0 hover:text-army-700">Register for Results</a>
        </div>
    </div>
</header>

@if(session('success'))
    <div class="bg-green-100 text-green-800 px-4 py-3 text-center text-sm">{{ session('success') }}</div>
@endif
@if(session('otp_sent'))
    <div class="bg-amber-100 text-amber-900 px-4 py-3 text-center text-sm">{{ session('otp_sent') }}</div>
@endif

<main>
    @yield('content')
</main>

{{-- FOOTER --}}
<footer class="bg-army-900 text-army-100 mt-20">
    <div class="max-w-7xl mx-auto px-4 py-14 grid md:grid-cols-4 gap-10 text-sm">
        <div>
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center font-display font-bold">TA</div>
                <div>
                    <div class="text-white font-display font-bold text-lg">{{ $siteTitle }}</div>
                    <div class="text-army-200 text-xs">सावधानी व शूरता</div>
                </div>
            </div>
            <p class="text-army-200 text-xs leading-relaxed">{{ $siteTagline }}</p>
        </div>
        <div>
            <div class="text-white font-semibold mb-3 uppercase text-xs tracking-wide">Join</div>
            @foreach($menu->take(6) as $item)
                <a href="{{ route('page.show', $item->slug) }}" class="block py-1 hover:text-white">{{ $item->menu_name }}</a>
            @endforeach
        </div>
        <div>
            <div class="text-white font-semibold mb-3 uppercase text-xs tracking-wide">Resources</div>
            <a href="{{ route('books.index') }}" class="block py-1 hover:text-white">Books</a>
            <a href="{{ route('pdfs.index') }}" class="block py-1 hover:text-white">PDFs / Downloads</a>
            <a href="{{ route('study.index') }}" class="block py-1 hover:text-white">Study Material</a>
            <a href="{{ route('exams.index') }}" class="block py-1 hover:text-white">Exams</a>
            <a href="{{ route('reviews') }}" class="block py-1 hover:text-white">Reviews</a>
        </div>
        <div>
            <div class="text-white font-semibold mb-3 uppercase text-xs tracking-wide">Company</div>
            <a href="{{ route('about') }}" class="block py-1 hover:text-white">About</a>
            <a href="{{ route('contact') }}" class="block py-1 hover:text-white">Contact</a>
            <a href="{{ route('privacy') }}" class="block py-1 hover:text-white">Privacy Policy</a>
            <a href="{{ route('terms') }}" class="block py-1 hover:text-white">Terms &amp; Conditions</a>
            <a href="#" class="block py-1 hover:text-white">Shipping &amp; Delivery</a>
            <a href="#" class="block py-1 hover:text-white">Refund &amp; Cancellation</a>
        </div>
    </div>
    <div class="border-t border-army-800 py-4 text-center text-xs text-army-200">
        &copy; {{ date('Y') }} {{ strtoupper($siteTitle) }}, INDIA. ALL RIGHTS RESERVED.
    </div>
</footer>

</body>
</html>
