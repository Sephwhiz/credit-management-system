<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Services\InterestService;
use Illuminate\Http\Request;

class AdminLoanController extends Controller
{
    /**
     * Display a list of ALL loans (not just current user's)
     */
    public function index(Request $request)
    {
        $query = Loan::with(['user', 'loanType'])->orderBy('created_at', 'desc');

        // Optional filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"))
                  ->orWhere('amount', 'like', "%{$search}%");
            });
        }

        $loans = $query->paginate(15);

        return view('admin.loans.index', compact('loans'));
    }

    /**
     * Approve a loan and calculate interest
     */
    public function approve(Loan $loan)
    {
        // Only approve pending loans
        if ($loan->status !== 'pending') {
            return back()->with('error', 'Only pending loans can be approved.');
        }

        // Calculate interest using service
        $calculation = InterestService::calculateTotalRepayable($loan);

        // Update loan record
        $loan->update([
            'status' => 'approved',
            'calculated_interest_amount' => $calculation['interest'],
            'total_repayable_amount' => $calculation['total'],
            // Note: custom_interest_rate would be set here if admin provided one
        ]);

        return back()->with('success', "Loan #{$loan->id} approved. Interest: \${$calculation['interest']}");
    }

    /**
     * Reject a loan
     */
    public function reject(Loan $loan)
    {
        if ($loan->status !== 'pending') {
            return back()->with('error', 'Only pending loans can be rejected.');
        }

        $loan->update(['status' => 'rejected']);

        return back()->with('success', "Loan #{$loan->id} has been rejected.");
    }
}