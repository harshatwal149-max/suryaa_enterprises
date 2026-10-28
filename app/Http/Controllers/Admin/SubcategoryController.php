<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SubcategoryController extends Controller
{
    /**
     * Display all subcategories.
     */
    public function index()
    {
        $subcategories = Subcategory::with('category')
            ->latest()
            ->paginate(10);

        return view(
            'admin.subcategories.index',
            compact('subcategories')
        );
    }

    /**
     * Show the create form.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view(
            'admin.subcategories.create',
            compact('categories')
        );
    }

    /**
     * Store a new subcategory.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => [
                'required',
                'integer',
                'exists:categories,id',
            ],
            'name' => ['required', 'string', 'max:255'],
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        $slug = Str::slug($validated['name']);

        if ($slug === '') {
            return back()
                ->withInput()
                ->withErrors([
                    'name' => 'Please enter a valid subcategory name.',
                ]);
        }

        $validated['slug'] = $this->uniqueSlug($slug);

        // New subcategories are active by default.
        $validated['is_active'] = true;

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')
                ->store('subcategories', 'public');
        }

        Subcategory::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Subcategory created successfully.',
            ]);
        }

        return redirect()
            ->route('admin.subcategories.index')
            ->with('success', 'Subcategory created successfully.');
    }

    /**
     * Redirect the unused show page.
     */
    public function show(Subcategory $subcategory)
    {
        return redirect()->route('admin.subcategories.index');
    }

    /**
     * Show the edit form.
     */
    public function edit(Subcategory $subcategory)
    {
        $categories = Category::orderBy('name')->get();

        return view(
            'admin.subcategories.edit',
            compact('subcategory', 'categories')
        );
    }

    /**
     * Update an existing subcategory.
     */
    public function update(Request $request, Subcategory $subcategory)
    {
        $validated = $request->validate([
            'category_id' => [
                'required',
                'integer',
                'exists:categories,id',
            ],
            'name' => ['required', 'string', 'max:255'],
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        $slug = Str::slug($validated['name']);

        if ($slug === '') {
            return back()
                ->withInput()
                ->withErrors([
                    'name' => 'Please enter a valid subcategory name.',
                ]);
        }

        $validated['slug'] = $this->uniqueSlug(
            $slug,
            $subcategory->id
        );

        $oldImage = $subcategory->image;
        $newImage = null;

        if ($request->hasFile('image')) {
            $newImage = $request->file('image')
                ->store('subcategories', 'public');

            $validated['image'] = $newImage;
        }

        try {
            $subcategory->update($validated);
        } catch (\Throwable $exception) {
            if ($newImage) {
                Storage::disk('public')->delete($newImage);
            }

            throw $exception;
        }

        if ($newImage && $oldImage && $oldImage !== $newImage) {
            Storage::disk('public')->delete($oldImage);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Subcategory updated successfully.',
            ]);
        }

        return redirect()
            ->route('admin.subcategories.index')
            ->with('success', 'Subcategory updated successfully.');
    }

    /**
     * Toggle subcategory active status.
     */
    public function toggleStatus(Subcategory $subcategory)
    {
        // Read the current value directly from the database.
        $subcategory->refresh();

        $currentStatus = (bool) $subcategory->is_active;

        // Save the opposite status directly, without mass assignment.
        $subcategory->is_active = ! $currentStatus;
        $subcategory->save();

        // Verify that the database contains the updated value.
        $subcategory->refresh();

        $newStatus = (bool) $subcategory->is_active;

        if ($newStatus === $currentStatus) {
            return redirect()
                ->route('admin.subcategories.index')
                ->with('error', 'Status could not be updated. Please try again.');
        }

        $message = $newStatus
            ? 'Subcategory activated successfully.'
            : 'Subcategory deactivated successfully.';

        return redirect()
            ->route('admin.subcategories.index')
            ->with('success', $message);
    }

    /**
     * Delete a subcategory.
     */
    public function destroy(Request $request, Subcategory $subcategory)
    {
        $image = $subcategory->image;

        $subcategory->delete();

        if ($image) {
            Storage::disk('public')->delete($image);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Subcategory deleted successfully.',
            ]);
        }

        return redirect()
            ->route('admin.subcategories.index')
            ->with('success', 'Subcategory deleted successfully.');
    }

    /**
     * Generate a unique slug.
     */
    private function uniqueSlug(
        string $slug,
        ?int $ignoreId = null
    ): string {
        $originalSlug = $slug;
        $counter = 1;

        while (
            Subcategory::where('slug', $slug)
                ->when(
                    $ignoreId !== null,
                    fn ($query) => $query->where('id', '!=', $ignoreId)
                )
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
