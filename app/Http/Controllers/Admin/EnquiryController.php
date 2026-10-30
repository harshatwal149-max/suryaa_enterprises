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
     * Display all customer enquiries.
     */
    public function index(): View
    {
        $enquiries = Enquiry::latest()->paginate(15);

        $undeliveredEmails = EnquiryNotifier::undeliveredCount();

        return view('admin.enquiries.index', compact('enquiries', 'undeliveredEmails'));
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
                'in:pending,contacted,completed',
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
