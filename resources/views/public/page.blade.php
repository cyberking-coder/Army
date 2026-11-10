@extends('layouts.public')

@section('title', $page->meta_title ?: $page->title)
@section('description', $page->meta_description ?: '')

@section('content')
<article class="max-w-4xl mx-auto px-4 py-10">
    <header class="mb-8 border-b pb-6">
        <h1 class="font-display font-bold text-3xl sm:text-4xl text-brand-900">{{ $page->title }}</h1>
        @if($page->parent)
            <nav class="text-sm text-gray-500 mt-2">
                <a href="{{ route('page.show', $page->parent->slug) }}" class="hover:text-brand-700">← {{ $page->parent->title }}</a>
            </nav>
        @endif
    </header>

    <div class="prose prose-lg max-w-none prose-headings:font-display prose-headings:text-brand-900 prose-a:text-brand-700">
        {!! $page->description !!}
    </div>

    @if($children->isNotEmpty())
        <section class="mt-10 pt-8 border-t">
            <h2 class="font-display font-bold text-xl mb-4">Related pages</h2>
            <ul class="space-y-2">
                @foreach($children as $child)
                    <li>
                        <a href="{{ route('page.show', $child->slug) }}" class="text-brand-700 hover:underline">
                            → {{ $child->title }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </section>
    @endif
</article>
@endsection
