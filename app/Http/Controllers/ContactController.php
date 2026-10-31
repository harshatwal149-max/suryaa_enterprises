<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Enquiry;
use App\Models\SiteSetting;
use App\Support\EnquiryNotifier;
use App\Support\EnquirySpamFilter;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function show()
    {
        $settings = SiteSetting::current();

        $categories = Category::query()
            ->whereHas('products', fn ($query) => $query->where('is_active', true))
            ->orderBy('name')
            ->get();

        $whatsappNumber = $settings?->whatsappDigits() ?? '';

        return view('contact-us', compact('settings', 'categories', 'whatsappNumber'));
    }

    public function submit(Request $request)
    {
        $successMessage = 'Thank you! Your message has been received. Our team will contact you shortly.';

        // Honeypot: real visitors never see or fill this field, bots do.
        if (filled($request->input('website'))) {
            return $request->ajax()
                ? response()->json(['success' => true, 'message' => $successMessage])
                : back()->with('contact_success', $successMessage);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:150'],
            'phone' => ['required', 'string', 'max:30', 'regex:/^[0-9+\-\s()]{7,30}$/'],
            'interest' => ['nullable', 'string', 'max:100'],
            'message' => ['required', 'string', 'max:5000'],
        ], [
            'phone.regex' => 'Please enter a valid phone number.',
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
            'status' => EnquirySpamFilter::isSpam($validated['phone'], $validated['message'])
                ? Enquiry::STATUS_SPAM
                : Enquiry::STATUS_PENDING,
        ]);

        EnquiryNotifier::send($enquiry, 'Contact Us page');

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
            ]);
        }

        return back()->with('contact_success', $successMessage);
    }
}
