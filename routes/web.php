<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\HeroSlideController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\EnquiryController as AdminEnquiryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;

use App\Http\Controllers\Frontend\ProductController as FrontendProductController;
use App\Http\Controllers\Frontend\TestimonialController as FrontendTestimonialController;

use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\ContactController;

use App\Models\HeroSlide;
use App\Models\SiteSetting;
use App\Models\Product;


/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/

// Homepage
Route::get('/', function () {
    $slides = HeroSlide::where('is_active', true)
        ->orderBy('sort_order')
        ->get();

    $products = Product::where('is_active', true)
        ->latest()
        ->take(8)
        ->get();

    $settings = SiteSetting::first();

    return view('welcome', compact(
        'slides',
        'products',
        'settings'
    ));
})->name('home');


// About Us
Route::view('/about-us', 'about-us')
    ->name('about');


// Contact Us
Route::get('/contact-us', [
    ContactController::class,
    'show'
])->name('contact');

Route::post('/contact-us', [
    ContactController::class,
    'submit'
])->name('contact.store');


// Enquiry Page
Route::get('/enquiry', function () {
    $settings = SiteSetting::first();

    return view('enquiry', compact('settings'));
})->name('enquiry');


// Submit Enquiry
Route::post('/enquiry', [
    EnquiryController::class,
    'store'
])->name('enquiry.store');


// Product Listing
Route::get('/products', [
    FrontendProductController::class,
    'index'
])->name('products.index');


// Product Details
Route::get('/products/{slug}', [
    FrontendProductController::class,
    'show'
])->name('products.show');


// Customer Reviews
Route::get('/write-review', [
    FrontendTestimonialController::class,
    'create'
])->name('testimonials.create');

Route::post('/write-review', [
    FrontendTestimonialController::class,
    'store'
])->name('testimonials.store');


/*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->group(function () {

    // Admin Login Page
    Route::get('/', [
        AuthController::class,
        'showLogin'
    ])->name('admin.login');

    // Process Login
    Route::post('/login', [
        AuthController::class,
        'login'
    ])->name('admin.login.submit');
});


/*
|--------------------------------------------------------------------------
| Protected Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->middleware('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [
            DashboardController::class,
            'index'
        ])->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | Enquiries
        |--------------------------------------------------------------------------
        */

        Route::get('/enquiries', [
            AdminEnquiryController::class,
            'index'
        ])->name('enquiries.index');

        Route::match(['post', 'patch'], '/enquiries/{enquiry}/status', [
            AdminEnquiryController::class,
            'updateStatus'
        ])->name('enquiries.update-status');

        Route::delete('/enquiries/{enquiry}', [
            AdminEnquiryController::class,
            'destroy'
        ])->name('enquiries.destroy');


        /*
        |--------------------------------------------------------------------------
        | General Website Settings
        |--------------------------------------------------------------------------
        */

        Route::get('/settings', [
            SiteSettingController::class,
            'edit'
        ])->name('settings.edit');

        Route::put('/settings', [
            SiteSettingController::class,
            'update'
        ])->name('settings.update');


        /*
        |--------------------------------------------------------------------------
        | Theme Colors
        |--------------------------------------------------------------------------
        */

        Route::get('/theme-colors', [
            SiteSettingController::class,
            'editThemeColors'
        ])->name('theme-colors.edit');

        Route::put('/theme-colors', [
            SiteSettingController::class,
            'updateThemeColors'
        ])->name('theme-colors.update');


        /*
        |--------------------------------------------------------------------------
        | Logo and Favicon
        |--------------------------------------------------------------------------
        */

        Route::get('/logo-favicon', [
            SiteSettingController::class,
            'editLogo'
        ])->name('settings.logo');

        Route::put('/logo-favicon', [
            SiteSettingController::class,
            'updateLogo'
        ])->name('settings.logo.update');


        /*
        |--------------------------------------------------------------------------
        | Contact Details
        |--------------------------------------------------------------------------
        */

        Route::get('/contact-details', [
            SiteSettingController::class,
            'editContact'
        ])->name('settings.contact');

        Route::put('/contact-details', [
            SiteSettingController::class,
            'updateContact'
        ])->name('settings.contact.update');


        /*
        |--------------------------------------------------------------------------
        | SEO Settings
        |--------------------------------------------------------------------------
        */

        Route::get('/seo', [
            SiteSettingController::class,
            'editSeo'
        ])->name('settings.seo');

        Route::put('/seo', [
            SiteSettingController::class,
            'updateSeo'
        ])->name('settings.seo.update');


        /*
        |--------------------------------------------------------------------------
        | Social Media Settings
        |--------------------------------------------------------------------------
        */

        Route::get('/social-media', [
            SiteSettingController::class,
            'editSocial'
        ])->name('settings.social');

        Route::put('/social-media', [
            SiteSettingController::class,
            'updateSocial'
        ])->name('settings.social.update');


        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */

        Route::match(['post', 'patch'], '/categories/{category}/toggle-status', [
            CategoryController::class,
            'toggleStatus'
        ])->name('categories.toggle-status');

        Route::resource('categories', CategoryController::class);


        /*
        |--------------------------------------------------------------------------
        | Subcategories
        |--------------------------------------------------------------------------
        */

        Route::match(['post', 'patch'], '/subcategories/{subcategory}/toggle-status', [
            SubcategoryController::class,
            'toggleStatus'
        ])->name('subcategories.toggle-status');

        Route::resource('subcategories', SubcategoryController::class);


        /*
        |--------------------------------------------------------------------------
        | Products and Gallery Images
        |--------------------------------------------------------------------------
        */

        Route::match(['post', 'patch'], '/products/{product}/toggle-status', [
            AdminProductController::class,
            'toggleStatus'
        ])->name('products.toggle-status');

        Route::delete('/products/{product}/images/{image}', [
            AdminProductController::class,
            'destroyImage'
        ])->name('products.images.destroy');

        Route::resource('products', AdminProductController::class);


        /*
        |--------------------------------------------------------------------------
        | Hero Slides
        |--------------------------------------------------------------------------
        */

        Route::match(['post', 'patch'], '/hero-slides/{hero_slide}/toggle-status', [
            HeroSlideController::class,
            'toggleStatus'
        ])->name('hero-slides.toggle-status');

        Route::resource('hero-slides', HeroSlideController::class)
            ->except(['show']);


        /*
        |--------------------------------------------------------------------------
        | Admin Testimonials
        |--------------------------------------------------------------------------
        */

        Route::match(['post', 'patch'], '/testimonials/{testimonial}/toggle-status', [
            TestimonialController::class,
            'toggleStatus'
        ])->name('testimonials.toggle-status');

        Route::resource('testimonials', TestimonialController::class)
            ->except(['show']);


        /*
        |--------------------------------------------------------------------------
        | Logout
        |--------------------------------------------------------------------------
        */

        Route::post('/logout', [
            AuthController::class,
            'logout'
        ])->name('logout');
    });