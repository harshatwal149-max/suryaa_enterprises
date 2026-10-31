<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Models\Product;
use App\Support\EnquiryNotifier;
use App\Support\EnquirySpamFilter;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnquiryController extends Controller
{
    public function store(Request $request): Response
    {
        $successMessage = 'Thank you! Your enquiry has been submitted successfully.';

        // Honeypot: real visitors never see or fill this field, bots do.
        if (filled($request->input('website'))) {
            return $request->ajax()
                ? response()->json(['success' => true, 'message' => $successMessage])
                : redirect()->to(url('/').'#enquiry')->with('success', $successMessage);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s()]{7,20}$/'],
            'email' => ['nullable', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
            'product_id' => ['nullable', 'integer'],
        ], [
            'phone.regex' => 'Please enter a valid phone number.',
        ]);

        $validated['status'] = EnquirySpamFilter::isSpam($validated['phone'], $validated['message'])
            ? Enquiry::STATUS_SPAM
            : Enquiry::STATUS_PENDING;

        // Enquiries sent from a product page say which product they are about.
        $product = filled($validated['product_id'] ?? null)
            ? Product::where('is_active', true)->find($validated['product_id'])
            : null;

        if ($product) {
            $validated['message'] = 'Product: '.$product->name
                .($product->sku ? ' (SKU: '.$product->sku.')' : '')."\n"
                .route('products.show', ['slug' => $product->slug])."\n\n"
                .$validated['message'];
        }

        unset($validated['product_id']);

        $enquiry = Enquiry::create($validated);

        EnquiryNotifier::send($enquiry, $product ? 'Product page' : 'Enquiry form');

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
            ]);
        }

        return redirect()
            ->to(url('/').'#enquiry')
            ->with('success', $successMessage);
    }
}
