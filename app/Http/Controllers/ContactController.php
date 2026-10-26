<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Enquiry;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['nullable', 'email', 'max:150'],
            'phone'    => ['required', 'string', 'max:30'],
            'interest' => ['nullable', 'string', 'max:100'],
            'message'  => ['required', 'string', 'max:5000'],
        ]);

        $message = filled($validated['interest'] ?? null)
            ? "Interested in: {$validated['interest']}\n\n{$validated['message']}"
            : $validated['message'];

        // Save first so the enquiry always reaches Admin → Enquiries, even if email fails.
        Enquiry::create([
            'name'    => $validated['name'],
            'phone'   => $validated['phone'],
            'email'   => $validated['email'] ?? null,
            'message' => $message,
        ]);

        $this->notifyByEmail($validated, $message);

        $successMessage = 'Thank you! Your message has been received. Our team will contact you shortly.';

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
            ]);
        }

        return back()->with('contact_success', $successMessage);
    }

    /** Best-effort email notification to the business email set in Admin → Settings. */
    private function notifyByEmail(array $validated, string $message): void
    {
        $recipient = SiteSetting::first()?->email ?: config('mail.from.address');

        if (!$recipient) {
            return;
        }

        try {
            Mail::raw(
                "Name: {$validated['name']}\n" .
                    'Email: ' . ($validated['email'] ?? 'Not provided') . "\n" .
                    "Phone: {$validated['phone']}\n\n" .
                    "Message:\n{$message}",
                function ($mail) use ($recipient, $validated) {
                    $mail->to($recipient)
                        ->subject('New Contact Us Enquiry');

                    if (!empty($validated['email'])) {
                        $mail->replyTo($validated['email'], $validated['name']);
                    }
                }
            );
        } catch (\Throwable $e) {
            Log::error('Contact enquiry email failed: ' . $e->getMessage());
        }
    }
}
