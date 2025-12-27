<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Merchant;
use App\Models\MerchantPayoutRequest;
use App\Models\PayoutSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MerchantPayoutController extends Controller
{
    /**
     * Display a listing of payout requests
     */
    public function index(Request $request)
    {
        $query = MerchantPayoutRequest::with(['merchant', 'subMerchant', 'cryptoCurrency', 'approvedBy'])
            ->orderBy('created_at', 'desc');

        // Filter by merchant
        if ($request->has('merchant_id') && $request->merchant_id) {
            $query->where('merchant_id', $request->merchant_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $payouts = $query->paginate(20);
        $merchants = Merchant::where('merchant_type', 'general')->orderBy('fullname')->get();

        return view('admin.payout.index', compact('payouts', 'merchants'));
    }

    /**
     * Show payout details
     */
    public function show($id)
    {
        $payout = MerchantPayoutRequest::with(['merchant', 'subMerchant', 'cryptoCurrency', 'approvedBy'])
            ->findOrFail($id);

        return view('admin.payout.details', compact('payout'));
    }

    /**
     * Show approve form
     */
    public function approveForm($id)
    {
        $payout = MerchantPayoutRequest::with(['merchant', 'subMerchant', 'cryptoCurrency'])
            ->findOrFail($id);

        if ($payout->status != 0) {
            return redirect()->back()->with('alert', 'This payout request has already been processed.');
        }

        return view('admin.payout.approve', compact('payout'));
    }

    /**
     * Approve payout request
     */
    public function approve(Request $request, $id)
    {
        $request->validate([
            'transaction_hash' => 'required|string|max:255',
            'admin_note' => 'nullable|string|max:500',
            'documents' => 'required|array|min:1',
            'documents.*' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120', // Max 5MB per file
        ], [
            'documents.required' => 'Please upload at least one proof document.',
            'documents.*.required' => 'Each document is required.',
            'documents.*.mimes' => 'Documents must be jpg, jpeg, png, pdf, doc, or docx.',
            'documents.*.max' => 'Each document must not exceed 5MB.',
        ]);

        DB::beginTransaction();

        try {
            $payout = MerchantPayoutRequest::findOrFail($id);

            if ($payout->status != 0) {
                return redirect()->back()->with('alert', 'This payout request has already been processed.');
            }

            // Handle document uploads
            $documentPaths = [];
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $index => $file) {
                    $filename = 'payout_' . $payout->payout_id . '_' . time() . '_' . $index . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('payout_documents', $filename, 'public');
                    $documentPaths[] = [
                        'filename' => $file->getClientOriginalName(),
                        'path' => $path,
                        'size' => $file->getSize(),
                        'uploaded_at' => now()->toDateTimeString(),
                    ];
                }
            }

            $payout->update([
                'status' => 4, // Completed
                'transaction_hash' => $request->transaction_hash,
                'admin_note' => $request->admin_note,
                'approval_documents' => json_encode($documentPaths),
                'approved_by' => Auth::guard('admin')->id(),
                'approved_at' => now(),
            ]);

            // Note: Balance was already deducted when request was created
            // No need to deduct again on approval

            DB::commit();

            return redirect()->route('admin.merchant-payout.index')
                ->with('message', 'Payout request approved successfully with ' . count($documentPaths) . ' document(s) uploaded.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Payout approval failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('alert', 'Something went wrong: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show reject form
     */
    public function rejectForm($id)
    {
        $payout = MerchantPayoutRequest::with(['merchant', 'subMerchant', 'cryptoCurrency'])
            ->findOrFail($id);

        if ($payout->status != 0) {
            return redirect()->back()->with('alert', 'This payout request has already been processed.');
        }

        return view('admin.payout.reject', compact('payout'));
    }

    /**
     * Reject payout request
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'reject_reason' => 'required|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            $payout = MerchantPayoutRequest::findOrFail($id);

            if ($payout->status != 0) {
                return redirect()->back()->with('alert', 'This payout request has already been processed.');
            }

            // Refund the balance that was deducted when request was created
            if ($payout->sub_merchant) {
                // For sub-merchant: refund to their balance
                Merchant::where('id', $payout->sub_merchant)->increment('balance', $payout->amount);
                // Also refund to main merchant's total balance
                Merchant::where('id', $payout->merchant_id)->increment('balance', $payout->amount);
            } else {
                // For general merchant: refund to available_balance (legal balance)
                Merchant::where('id', $payout->merchant_id)->increment('available_balance', $payout->amount);
                // Also refund to total balance
                Merchant::where('id', $payout->merchant_id)->increment('balance', $payout->amount);
            }

            $payout->update([
                'status' => 3, // Rejected
                'reject_reason' => $request->reject_reason,
                'approved_by' => Auth::guard('admin')->id(),
                'approved_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.merchant-payout.index')
                ->with('message', 'Payout request rejected and balance refunded.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('alert', 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * Update payout status (for processing)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:0,1,2,3,4',
        ]);

        $payout = MerchantPayoutRequest::findOrFail($id);
        $payout->status = $request->status;
        $payout->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
        ]);
    }
}
