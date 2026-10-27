<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\HeroSlide;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class HomeController extends Controller
{
    /** Display the homepage with categories, products, deals and reviews. */
    public function index(): View
    {
        // Skip slides whose image file is missing so the hero never renders blank.
        $slides = HeroSlide::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->filter(fn (HeroSlide $slide) => $slide->image_path
                && Storage::disk('public')->exists($slide->image_path))
            ->values();

        $activeProducts = fn ($query) => $query->where('is_active', true);

        // Categories that have at least one active product, with a cover image.
        $categories = Category::query()
            ->where('is_active', true)
            ->whereHas('products', $activeProducts)
            ->withCount(['products' => $activeProducts])
            ->with(['products' => fn ($query) => $query
                ->where('is_active', true)
                ->whereNotNull('image')
                ->latest()])
            ->orderBy('name')
            ->get()
            ->each(function (Category $category) {
                $category->cover_image = $category->image
                    ?: $category->products->first()?->image;

                $category->unsetRelation('products');
            });

        $heroSlides = $this->heroSlides($slides, $categories);

        // Latest products for the tabbed product grid.
        $products = Product::with('category')
            ->where('is_active', true)
            ->latest()
            ->take(24)
            ->get();

        // Biggest discounts first for the "Top Deals" carousel; the section
        // disappears when prices are switched off site-wide.
        $deals = ! SiteSetting::pricesVisible() ? collect() : Product::with('category')
            ->where('is_active', true)
            ->where('show_price', true)
            ->whereNotNull('discount_price')
            ->where('discount_price', '>', 0)
            ->whereColumn('discount_price', '<', 'price')
            ->orderByRaw('(price - discount_price) / price DESC')
            ->take(10)
            ->get();

        $testimonials = Testimonial::where('is_active', true)
            ->latest()
            ->take(3)
            ->get();

        $stats = [
            'products' => Product::where('is_active', true)->count(),
            'categories' => $categories->count(),
            'reviews' => Testimonial::where('is_active', true)->count(),
        ];

        $settings = SiteSetting::first();

        $whatsappNumber = $settings?->whatsappDigits() ?? '';

        $phoneNumber = (string) ($settings?->phone_primary ?? '');

        return view('welcome', compact(
            'heroSlides',
            'categories',
            'products',
            'deals',
            'testimonials',
            'stats',
            'settings',
            'whatsappNumber',
            'phoneNumber'
        ));
    }

    /**
     * Slides for the hero carousel. Admin-uploaded slides win; otherwise one
     * slide per product category is generated from its cover photo.
     *
     * @return array<int, array{image: ?string, kicker: ?string, heading: string, description: ?string, label: string, primary: ?array, secondary: ?array}>
     */
    private function heroSlides(Collection $slides, Collection $categories): array
    {
        if ($slides->isNotEmpty()) {
            return $slides->map(fn (HeroSlide $slide) => [
                'image' => $slide->image_path,
                'kicker' => null,
                'heading' => $slide->heading ?: 'Suryaa Enterprises',
                'description' => $slide->description,
                'label' => Str::limit($slide->heading ?: 'Suryaa Enterprises', 22),
                'primary' => $slide->button_text && $slide->button_url
                    ? ['text' => $slide->button_text, 'url' => $slide->button_url]
                    : null,
                'secondary' => ['text' => 'Get a Free Quote', 'url' => '#hero-quote'],
            ])->all();
        }

        $copy = [
            'solar-power-plants' => [
                'kicker' => 'PM Surya Ghar Subsidy Assistance',
                'heading' => 'Rooftop Solar Power Plants',
                'description' => 'Cut your electricity bill with on-grid, off-grid and hybrid systems. We help with net metering and subsidy paperwork.',
                'label' => 'Rooftop Solar',
            ],
            'solar-water-heater' => [
                'kicker' => 'Built for Himachal Winters',
                'heading' => 'Glass Line Solar Water Heaters',
                'description' => 'Corrosion-resistant tanks and high-efficiency tubes for hot water all year, from 100 to 500 LPD.',
                'label' => 'Water Heaters',
            ],
            'solar-street-lights' => [
                'kicker' => 'Dusk to Dawn, Zero Bills',
                'heading' => 'Solar Street Lights',
                'description' => 'All-in-one and semi-integrated LED street lights for village roads, colonies and campuses.',
                'label' => 'Street Lights',
            ],
            'solar-fencing' => [
                'kicker' => 'Protect Crops & Orchards',
                'heading' => 'Solar Fencing Systems',
                'description' => 'Keep wild animals and monkeys away from your farm with complete solar fencing kits.',
                'label' => 'Solar Fencing',
            ],
            'solar-home-lighting' => [
                'kicker' => 'Power Through Every Cut',
                'heading' => 'Solar Home Lighting & Inverters',
                'description' => 'Lanterns, home lighting kits and solar inverters that keep your home running.',
                'label' => 'Home Lighting',
            ],
        ];

        $slidesFromCategories = $categories
            ->filter(fn (Category $category) => isset($copy[$category->slug]) && $category->cover_image)
            ->sortBy(fn (Category $category) => array_search($category->slug, array_keys($copy)))
            ->map(fn (Category $category) => $copy[$category->slug] + [
                'image' => $category->cover_image,
                'primary' => ['text' => 'Explore ' . $copy[$category->slug]['label'], 'url' => route('products.index', ['category' => $category->id])],
                'secondary' => ['text' => 'Get a Free Quote', 'url' => '#hero-quote'],
            ])
            ->values()
            ->all();

        return $slidesFromCategories ?: [[
            'image' => null,
            'kicker' => 'Authorised Super Solar Distributor · Himachal Pradesh',
            'heading' => 'Solar Solutions for Every Home, Farm & Business',
            'description' => 'Solar water heaters, rooftop power plants, street lights and fencing, supplied and installed by Suryaa Enterprises, Dharamshala.',
            'label' => 'Suryaa Enterprises',
            'primary' => ['text' => 'Explore Products', 'url' => route('products.index')],
            'secondary' => ['text' => 'Get a Free Quote', 'url' => '#hero-quote'],
        ]];
    }
}
