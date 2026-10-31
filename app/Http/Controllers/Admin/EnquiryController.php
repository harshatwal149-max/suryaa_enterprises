<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Support\EnquiryNotifier;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class EnquiryController extends Controller
{
    /**
     * Display customer enquiries, or the ones caught as spam.
     */
    public function index(Request $request): View
    {
        $showingSpam = $request->query('view') === 'spam';

        $enquiries = Enquiry::query()
            ->when($showingSpam, fn ($query) => $query->spam(), fn ($query) => $query->notSpam())
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $spamCount = Enquiry::spam()->count();

        $undeliveredEmails = EnquiryNotifier::undeliveredCount();

        return view('admin.enquiries.index', compact('enquiries', 'undeliveredEmails', 'showingSpam', 'spamCount'));
    }

    /**
     * Update the status of an enquiry.
     */
    public function updateStatus(
        Request $request,
        Enquiry $enquiry
    ): Response {
        $validated = $request->validate([
            'status' => [
                'required',
                'in:pending,contacted,completed,spam',
            ],
        ]);

        $enquiry->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'status' => $enquiry->status,
                'message' => 'Enquiry status updated to '.ucfirst($enquiry->status).'.',
            ]);
        }

        return redirect()
            ->route('admin.enquiries.index')
            ->with('success', 'Enquiry status updated successfully.');
    }

    /**
     * Delete an enquiry.
     */
    public function destroy(
        Request $request,
        Enquiry $enquiry
    ): Response {
        $enquiry->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Enquiry deleted successfully.',
            ]);
        }

        return redirect()
            ->route('admin.enquiries.index')
            ->with('success', 'Enquiry deleted successfully.');
    }
}
