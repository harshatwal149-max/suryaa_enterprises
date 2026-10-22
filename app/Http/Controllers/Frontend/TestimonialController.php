<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function create()
    {
        return view('testimonials.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'designation' => ['nullable', 'string', 'max:255'],
            'rating' => [
                'required',
                'numeric',
                'min:1',
                'max:5',
                'regex:/^(?:[1-4](?:\.5)?|5)$/',
            ],
            'review' => ['required', 'string', 'max:2000'],
        ]);

        Testimonial::create([
            'customer_name' => $validated['customer_name'],
            'designation' => $validated['designation'] ?? null,
            'rating' => $validated['rating'],
            'review' => $validated['review'],
            'is_active' => false,
        ]);

        return redirect()
            ->route('testimonials.create')
            ->with(
                'success',
                'Thank you! Your review has been submitted for approval.'
            );
    }
}
