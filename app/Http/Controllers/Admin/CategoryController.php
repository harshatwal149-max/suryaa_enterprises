<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display all categories.
     */
    public function index()
    {
        $categories = Category::latest()->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the create category form.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a new category.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:categories,name',
            ],
            'slug' => [
                'nullable',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        // Generate slug from the name if slug is empty.
        $slugInput = trim($validated['slug'] ?? '');

        $validated['slug'] = Str::slug(
            $slugInput !== '' ? $slugInput : $validated['name']
        );

        // Prevent duplicate slugs.
        if (
            Category::where('slug', $validated['slug'])->exists()
        ) {
            return back()
                ->withErrors([
                    'slug' => 'Ye slug pehle se use ho raha hai. Dusra slug enter karein.',
                ])
                ->withInput();
        }

        // Upload category image.
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')
                ->store('categories', 'public');
        }

        // Checkbox unchecked means inactive.
        $validated['is_active'] = $request->boolean('is_active');

        Category::create($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category successfully add ho gayi!');
    }

    /**
     * Show a single category.
     */
    public function show(Category $category)
    {
        return redirect()->route('admin.categories.index');
    }

    /**
     * Show the edit category form.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update an existing category.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')
                    ->ignore($category->id),
            ],
            'slug' => [
                'nullable',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        // Generate slug if empty.
        $slugInput = trim($validated['slug'] ?? '');

        $validated['slug'] = Str::slug(
            $slugInput !== '' ? $slugInput : $validated['name']
        );

        // Prevent duplicate slugs, excluding the current category.
        $slugExists = Category::where('slug', $validated['slug'])
            ->where('id', '!=', $category->id)
            ->exists();

        if ($slugExists) {
            return back()
                ->withErrors([
                    'slug' => 'Ye slug kisi aur category ka hai. Dusra slug enter karein.',
                ])
                ->withInput();
        }

        // Replace image only when a new one is uploaded.
        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            $validated['image'] = $request->file('image')
                ->store('categories', 'public');
        }

        // Checkbox unchecked means inactive.
        $validated['is_active'] = $request->boolean('is_active');

        $category->update($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category successfully updated!');
    }

    /**
     * Toggle category status between Active and Inactive.
     */
    public function toggleStatus(Request $request, Category $category)
    {
        $category->is_active = ! $category->is_active;
        $category->save();

        $status = $category->is_active ? 'Active' : 'Inactive';
        $message = "Category status successfully {$status} kar diya gaya!";

        if ($request->ajax()) {
            return response()->json([
                'success'   => true,
                'is_active' => $category->is_active,
                'status'    => $status,
                'message'   => $message,
            ]);
        }

        return redirect()
            ->route('admin.categories.index')
            ->with('success', $message);
    }

    /**
     * Delete a category and its stored image.
     */
    public function destroy(Request $request, Category $category)
    {
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Category successfully deleted!',
            ]);
        }

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category successfully deleted!');
    }
}
