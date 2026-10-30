<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Enquiry;
use App\Models\SiteSetting;
use App\Support\EnquiryNotifier;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function show()
    {
        $settings = SiteSetting::first();

        $categories = Category::query()
            ->whereHas('products', fn ($query) => $query->where('is_active', true))
            ->orderBy('name')
            ->get();

        $whatsappNumber = $settings?->whatsappDigits() ?? '';

        return view('contact-us', compact('settings', 'categories', 'whatsappNumber'));
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:150'],
            'phone' => ['required', 'string', 'max:30'],
            'interest' => ['nullable', 'string', 'max:100'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $message = filled($validated['interest'] ?? null)
            ? "Interested in: {$validated['interest']}\n\n{$validated['message']}"
            : $validated['message'];

        // Save first so the enquiry always reaches Admin → Enquiries, even if email fails.
        $enquiry = Enquiry::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'message' => $message,
        ]);

        EnquiryNotifier::send($enquiry, 'Contact Us page');

        $successMessage = 'Thank you! Your message has been received. Our team will contact you shortly.';

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
            ]);
        }

        return back()->with('contact_success', $successMessage);
    }
}
