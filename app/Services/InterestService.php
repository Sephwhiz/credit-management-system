<?php

namespace App\Services;

use App\Models\Loan;
use App\Models\LoanType;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InterestService
{
    private const GLOBAL_DEFAULT_RATE = 5.00;

    public static function calculateSimpleInterest(float $principal, float $rate, int $days): float
    {
        if ($days <= 0 || $principal <= 0) {
            return 0.00;
        }

        $interest = $principal * ($rate / 100) * ($days / 365);
        return round($interest, 2);
    }

    public static function getEffectiveRate(Loan $loan): float
    {
        if ($loan->custom_interest_rate !== null && $loan->custom_interest_rate > 0) {
            return (float) $loan->custom_interest_rate;
        }

        $loanType = $loan->loanType;
        if ($loanType && $loanType->default_interest_rate > 0) {
            return (float) $loanType->default_interest_rate;
        }

        return self::GLOBAL_DEFAULT_RATE;
    }

    public static function calculateTotalRepayable(Loan $loan): array
    {
        $rate = self::getEffectiveRate($loan);
        $principal = (float) $loan->amount;
        
        $dueDate = $loan->due_date ? Carbon::parse($loan->due_date) : Carbon::now()->addMonths(12);
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

    public static function getOverdueCount(): int
    {
        return Loan::where('status', '!=', 'paid')
            ->whereNotNull('due_date')
            ->where('due_date', '<', Carbon::today())
            ->count();
    }

    /**
     * Get total interest earned YTD - SAFE VERSION
     * Calculates interest dynamically since 'calculated_interest_amount' may not exist
     */
    public static function getYtdInterestEarned(): float
    {
        // Check if column exists first
        $hasColumn = DB::getSchemaBuilder()->hasColumn('loans', 'calculated_interest_amount');
        
        if ($hasColumn) {
            return (float) Loan::whereYear('created_at', Carbon::now()->year)
                ->where('status', 'approved')
                ->sum('calculated_interest_amount');
        }

        // Fallback: Calculate from approved loans using default rate
        $approvedLoans = Loan::whereYear('created_at', Carbon::now()->year)
            ->where('status', 'approved')
            ->get();

        $totalInterest = 0.0;
        foreach ($approvedLoans as $loan) {
            $calc = self::calculateTotalRepayable($loan);
            $totalInterest += $calc['interest'];
        }

        return round($totalInterest, 2);
    }
}