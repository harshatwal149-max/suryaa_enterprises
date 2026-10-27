<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
@foreach ($pages as $page)
    <url>
        <loc>{{ $page['loc'] }}</loc>
@if ($page['lastmod'])
        <lastmod>{{ \Illuminate\Support\Carbon::parse($page['lastmod'])->toAtomString() }}</lastmod>
@endif
        <changefreq>{{ $page['changefreq'] }}</changefreq>
        <priority>{{ $page['priority'] }}</priority>
    </url>
@endforeach
@foreach ($products as $product)
    <url>
        <loc>{{ route('products.show', ['slug' => $product->slug]) }}</loc>
        <lastmod>{{ $product->updated_at?->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
@foreach (collect([$product->image])->merge($product->images->pluck('image_path'))->filter()->unique() as $imagePath)
        <image:image>
            <image:loc>{{ asset('storage/' . ltrim($imagePath, '/')) }}</image:loc>
        </image:image>
@endforeach
    </url>
@endforeach
</urlset>
