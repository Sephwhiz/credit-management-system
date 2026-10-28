<?php

namespace App\Services;

use App\Models\Loan;
use App\Models\LoanType;
use Illuminate\Support\Facades\Auth;

class LoanService
{
    /**
     * Apply for a new loan
     */
    public function applyForLoan(array $data): Loan
    {
        return Loan::create([
            'user_id'      => Auth::id(),
            'loan_type_id' => $data['loan_type_id'],
            'amount'       => $data['amount'],
            'purpose'      => $data['purpose'] ?? null,
            'status'       => 'pending',
            'due_date'     => $data['due_date'] ?? null,
        ]);
    }

    /**
     * Get all loans for the authenticated user
     */
    public function getUserLoans()
    {
        return Loan::with(['loanType', 'repayments'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Admin: Approve or reject a loan
     */
    public function updateLoanStatus(int $loanId, string $status): Loan
    {
        $loan = Loan::findOrFail($loanId);
        $loan->update(['status' => $status]);
        return $loan;
    }

    /**
     * Get all pending loans (for admin dashboard)
     */
    public function getPendingLoans()
    {
        return Loan::with(['user', 'loanType'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();
    }
}