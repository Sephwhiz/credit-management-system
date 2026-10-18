<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\User;
use App\Services\InterestService;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // KPI 1: Total Disbursed Amount (Sum of approved loans)
        $totalDisbursed = Loan::where('status', 'approved')
            ->sum('amount');

        // KPI 2: Pending Approvals Count
        $pendingApprovals = Loan::where('status', 'pending')->count();

        // KPI 3: Overdue Loans Count (from InterestService)
        $overdueLoans = InterestService::getOverdueCount();

        // KPI 4: Total Interest Earned YTD (from InterestService)
        $ytdInterest = InterestService::getYtdInterestEarned();

        // KPI 5: New Users This Week
        $newUsersThisWeek = User::where('created_at', '>=', Carbon::now()->subWeek())
            ->count();

        return view('admin.dashboard', compact(
            'totalDisbursed',
            'pendingApprovals',
            'overdueLoans',
            'ytdInterest',
            'newUsersThisWeek'
        ));
    }
}