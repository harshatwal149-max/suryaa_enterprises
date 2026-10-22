<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class HeroSlideController extends Controller
{
    public function index()
    {
        $slides = HeroSlide::orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate(10);

        return view('admin.hero-slides.index', compact('slides'));
    }

    public function create()
    {
        return view('admin.hero-slides.create');
    }

    public function store(Request $request): Response
    {
        $validated = $request->validate([
            'image'       => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'heading'     => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'button_text' => ['nullable', 'string', 'max:100'],
            'button_url'  => ['nullable', 'string', 'max:2048'],
            'sort_order'  => ['required', 'integer', 'min:0'],
            'is_active'   => ['nullable', 'boolean'],
        ]);

        $validated['image_path'] = $request->file('image')
            ->store('hero-slides', 'public');

        unset($validated['image']);
        $validated['is_active'] = $request->boolean('is_active');

        HeroSlide::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Hero slide created successfully.',
            ]);
        }

        return redirect()
            ->route('admin.hero-slides.index')
            ->with('success', 'Hero slide created successfully.');
    }

    public function edit(HeroSlide $heroSlide)
    {
        return view('admin.hero-slides.edit', compact('heroSlide'));
    }

    public function update(Request $request, HeroSlide $heroSlide): Response
    {
        $validated = $request->validate([
            'image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'heading'     => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'button_text' => ['nullable', 'string', 'max:100'],
            'button_url'  => ['nullable', 'string', 'max:2048'],
            'sort_order'  => ['required', 'integer', 'min:0'],
            'is_active'   => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            $newPath = $request->file('image')
                ->store('hero-slides', 'public');

            if ($heroSlide->image_path) {
                Storage::disk('public')->delete($heroSlide->image_path);
            }

            $validated['image_path'] = $newPath;
        }

        unset($validated['image']);
        $validated['is_active'] = $request->boolean('is_active');

        $heroSlide->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Hero slide updated successfully.',
            ]);
        }

        return redirect()
            ->route('admin.hero-slides.index')
            ->with('success', 'Hero slide updated successfully.');
    }

    /**
     * Toggle hero slide status between Active and Inactive.
     */
    public function toggleStatus(Request $request, HeroSlide $heroSlide): Response
    {
        $heroSlide->is_active = ! $heroSlide->is_active;
        $heroSlide->save();

        $status = $heroSlide->is_active ? 'Active' : 'Inactive';
        $message = "Slide status successfully {$status} kar diya gaya!";

        if ($request->ajax()) {
            return response()->json([
                'success'   => true,
                'is_active' => $heroSlide->is_active,
                'status'    => $status,
                'message'   => $message,
            ]);
        }

        return redirect()
            ->route('admin.hero-slides.index')
            ->with('success', $message);
    }

    /**
     * Delete a hero slide and its image.
     */
    public function destroy(Request $request, HeroSlide $heroSlide): Response
    {
        if ($heroSlide->image_path) {
            Storage::disk('public')->delete($heroSlide->image_path);
        }

        $heroSlide->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Hero slide deleted successfully.',
            ]);
        }

        return redirect()
            ->route('admin.hero-slides.index')
            ->with('success', 'Hero slide deleted successfully.');
    }
}
