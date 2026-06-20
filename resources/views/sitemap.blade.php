<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xhtml="http://www.w3.org/1999/xhtml">

    {{-- Static Pages --}}
    @foreach([
        ['url' => route('home'), 'freq' => 'weekly', 'priority' => '1.0'],
        ['url' => route('about'), 'freq' => 'monthly', 'priority' => '0.8'],
        ['url' => route('gallery.index'), 'freq' => 'weekly', 'priority' => '0.9'],
        ['url' => route('order.create'), 'freq' => 'monthly', 'priority' => '0.9'],
        ['url' => route('shop.index'), 'freq' => 'weekly', 'priority' => '0.8'],
        ['url' => route('courses.index'), 'freq' => 'weekly', 'priority' => '0.8'],
        ['url' => route('blog.index'), 'freq' => 'weekly', 'priority' => '0.8'],
        ['url' => route('contact'), 'freq' => 'monthly', 'priority' => '0.6'],
        ['url' => route('faq'), 'freq' => 'monthly', 'priority' => '0.6'],
    ] as $page)
    <url>
        <loc>{{ $page['url'] }}</loc>
        <changefreq>{{ $page['freq'] }}</changefreq>
        <priority>{{ $page['priority'] }}</priority>
        <xhtml:link rel="alternate" hreflang="ar" href="{{ $page['url'] }}"/>
    </url>
    @endforeach

    {{-- Artworks --}}
    @foreach($artworks as $artwork)
    <url>
        <loc>{{ route('gallery.show', $artwork->slug) }}</loc>
        <lastmod>{{ $artwork->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

    {{-- Blog Posts --}}
    @foreach($posts as $post)
    <url>
        <loc>{{ route('blog.show', $post->slug) }}</loc>
        <lastmod>{{ $post->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    @endforeach

    {{-- Courses --}}
    @foreach($courses as $course)
    <url>
        <loc>{{ route('courses.show', $course->slug) }}</loc>
        <lastmod>{{ $course->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

    {{-- Products --}}
    @foreach($products as $product)
    <url>
        <loc>{{ route('shop.show', $product->slug) }}</loc>
        <lastmod>{{ $product->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
    </url>
    @endforeach

</urlset>
