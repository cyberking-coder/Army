<!doctype html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', $siteTitle)</title>
    <meta name="description" content="@yield('description', $siteTagline)">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            fontFamily: {
              sans: ['Inter','system-ui','sans-serif'],
              display: ['Poppins','Inter','sans-serif'],
            },
            colors: {
              brand: {
                50: '#F5F3FF', 100: '#EDE9FE', 500: '#A855F7',
                700: '#6B21A8', 900: '#4C1D95',
              }
            }
          }
        }
      }
    </script>
    <style>[x-cloak]{display:none!important}</style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
</head>
<body class="font-sans text-gray-900 bg-white antialiased">

<header class="bg-brand-700 text-white sticky top-0 z-50 shadow">
    <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
        <a href="{{ route('home') }}" class="font-display font-bold text-lg sm:text-xl">
            {{ $siteTitle }}
        </a>

        <nav class="hidden md:flex items-center gap-6 text-sm">
            <a href="{{ route('home') }}" class="hover:text-brand-100">Home</a>
            @foreach($menu as $item)
                @if($item->children->isEmpty())
                    <a href="{{ route('page.show', $item->slug) }}" class="hover:text-brand-100">{{ $item->menu_name }}</a>
                @else
                    <div class="relative group">
                        <button class="flex items-center gap-1 hover:text-brand-100">
                            {{ $item->menu_name }}
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="absolute left-0 top-full mt-0 w-56 bg-white text-gray-900 rounded-lg shadow-lg py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition">
                            @foreach($item->children as $child)
                                <a href="{{ route('page.show', $child->slug) }}" class="block px-4 py-2 hover:bg-brand-50">{{ $child->menu_name }}</a>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
            <a href="{{ route('books.index') }}" class="hover:text-brand-100">Books</a>
            <a href="{{ route('pdfs.index') }}" class="hover:text-brand-100">PDFs</a>
            <a href="{{ route('exams.index') }}" class="hover:text-brand-100">Exams</a>
            <a href="{{ route('contact') }}" class="hover:text-brand-100">Contact</a>
        </nav>

        <div x-data="{open:false}" class="md:hidden">
            <button @click="open=!open" class="p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div x-show="open" x-cloak @click.outside="open=false"
                 class="absolute top-full right-0 mt-0 w-64 bg-white text-gray-900 rounded-l-lg shadow-xl py-2">
                <a href="{{ route('home') }}" class="block px-4 py-2 hover:bg-brand-50">Home</a>
                @foreach($menu as $item)
                    <a href="{{ route('page.show', $item->slug) }}" class="block px-4 py-2 hover:bg-brand-50">{{ $item->menu_name }}</a>
                @endforeach
                <a href="{{ route('books.index') }}" class="block px-4 py-2 hover:bg-brand-50">Books</a>
                <a href="{{ route('pdfs.index') }}" class="block px-4 py-2 hover:bg-brand-50">PDFs</a>
                <a href="{{ route('exams.index') }}" class="block px-4 py-2 hover:bg-brand-50">Exams</a>
                <a href="{{ route('contact') }}" class="block px-4 py-2 hover:bg-brand-50">Contact</a>
            </div>
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

<footer class="bg-gray-900 text-gray-300 mt-20">
    <div class="max-w-6xl mx-auto px-4 py-12 grid md:grid-cols-4 gap-8 text-sm">
        <div>
            <div class="text-white font-display font-bold text-lg mb-3">{{ $siteTitle }}</div>
            <p class="text-gray-400">{{ $siteTagline }}</p>
        </div>
        <div>
            <div class="text-white font-semibold mb-2">Join</div>
            @foreach($menu as $item)
                <a href="{{ route('page.show', $item->slug) }}" class="block py-1 hover:text-white">{{ $item->menu_name }}</a>
            @endforeach
        </div>
        <div>
            <div class="text-white font-semibold mb-2">Resources</div>
            <a href="{{ route('books.index') }}" class="block py-1 hover:text-white">Books</a>
            <a href="{{ route('pdfs.index') }}" class="block py-1 hover:text-white">PDFs</a>
            <a href="{{ route('study.index') }}" class="block py-1 hover:text-white">Study Material</a>
            <a href="{{ route('exams.index') }}" class="block py-1 hover:text-white">Exams</a>
        </div>
        <div>
            <div class="text-white font-semibold mb-2">Company</div>
            <a href="{{ route('about') }}" class="block py-1 hover:text-white">About</a>
            <a href="{{ route('contact') }}" class="block py-1 hover:text-white">Contact</a>
            <a href="{{ route('privacy') }}" class="block py-1 hover:text-white">Privacy</a>
            <a href="{{ route('terms') }}" class="block py-1 hover:text-white">Terms</a>
        </div>
    </div>
    <div class="border-t border-gray-800 py-4 text-center text-xs text-gray-500">
        &copy; {{ date('Y') }} {{ $siteTitle }}. All rights reserved.
    </div>
</footer>

</body>
</html>
