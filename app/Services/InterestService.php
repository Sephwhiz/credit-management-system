<?php

namespace App\Services;

use App\Models\Loan;
use App\Models\LoanType;
use Carbon\Carbon;

class InterestService
{
    /**
     * Global default interest rate (fallback if loan type has none)
     */
    private const GLOBAL_DEFAULT_RATE = 5.00;

    /**
     * Calculate simple interest for a loan based on principal, rate, and term in days.
     * Formula: I = P × R × T / 365
     * 
     * @param float $principal Loan amount
     * @param float $rate Annual interest rate (e.g., 5.00 for 5%)
     * @param int $days Loan term in days (from approval to due date)
     * @return float Calculated interest amount
     */
    public static function calculateSimpleInterest(float $principal, float $rate, int $days): float
    {
        if ($days <= 0 || $principal <= 0) {
            return 0.00;
        }

        // Simple Interest: Principal × Rate × Time(years)
        $interest = $principal * ($rate / 100) * ($days / 365);
        
        return round($interest, 2);
    }

    /**
     * Determine the effective interest rate using hierarchy:
     * 1. Custom override on loan (if set by admin during approval)
     * 2. Loan type's default rate
     * 3. Global fallback rate
     */
    public static function getEffectiveRate(Loan $loan): float
    {
        // Priority 1: Custom override
        if ($loan->custom_interest_rate !== null && $loan->custom_interest_rate > 0) {
            return (float) $loan->custom_interest_rate;
        }

        // Priority 2: Loan type default
        $loanType = $loan->loanType;
        if ($loanType && $loanType->default_interest_rate > 0) {
            return (float) $loanType->default_interest_rate;
        }

        // Priority 3: Global fallback
        return self::GLOBAL_DEFAULT_RATE;
    }

    /**
     * Calculate total repayable amount (Principal + Interest)
     */
    public static function calculateTotalRepayable(Loan $loan): array
    {
        $rate = self::getEffectiveRate($loan);
        $principal = (float) $loan->amount;
        
        // Calculate days between now (approval time) and due date
        $dueDate = $loan->due_date ? Carbon::parse($loan->due_date) : Carbon::now()->addMonths(12);
        
        // FIX: Explicitly cast to int with floor() to prevent deprecation warning
        // and ensure we only charge for complete days (no fractional overcharging)
        $days = (int) max(1, floor(Carbon::now()->diffInDays($dueDate)));

        $interest = self::calculateSimpleInterest($principal, $rate, $days);
        $total = round($principal + $interest, 2);

        return [
            'rate' => $rate,
            'interest' => $interest,
            'total' => $total,
            'days' => $days
        ];
    }

    /**
     * Get count of overdue loans (past due date AND not paid)
     */
    public static function getOverdueCount(): int
    {
        return Loan::where('status', '!=', 'paid')
            ->whereNotNull('due_date')
            ->where('due_date', '<', Carbon::today())
            ->count();
    }

    /**
     * Get total interest earned YTD (from approved loans this calendar year)
     */
    public static function getYtdInterestEarned(): float
    {
        return Loan::whereYear('created_at', Carbon::now()->year)
            ->where('status', 'approved')
            ->sum('calculated_interest_amount');
    }
}