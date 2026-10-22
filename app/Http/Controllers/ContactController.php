<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show()
    {
        $settings = SiteSetting::first();

        return view('contact-us', compact('settings'));
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:100'],
            'email'   => ['nullable', 'email', 'max:150'],
            'phone'   => ['required', 'string', 'max:30'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $recipient = config('mail.from.address');

        if ($recipient) {
            try {
                Mail::raw(
                    "Name: {$validated['name']}\n" .
                        "Email: " . ($validated['email'] ?? 'Not provided') . "\n" .
                        "Phone: {$validated['phone']}\n\n" .
                        "Message:\n{$validated['message']}",
                    function ($mail) use ($recipient, $validated) {
                        $mail->to($recipient)
                            ->subject('New Contact Us Enquiry');

                        if (!empty($validated['email'])) {
                            $mail->replyTo(
                                $validated['email'],
                                $validated['name']
                            );
                        }
                    }
                );
            } catch (\Throwable $e) {
                Log::error('Contact enquiry email failed: ' . $e->getMessage());

                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Message send nahi hua. Please try again.'
                    ], 500);
                }

                return back()->withInput()->with(
                    'contact_error',
                    'Message send nahi hua. Please try again.'
                );
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you! Your enquiry has been submitted successfully.'
            ]);
        }

        return back()->with(
            'contact_success',
            'Thank you! Your enquiry has been submitted successfully.'
        );
    }
}
