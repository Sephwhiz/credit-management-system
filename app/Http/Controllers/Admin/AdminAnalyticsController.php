<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->input('filter', 'month');
        
        $startDate = match($filter) {
            'today' => now()->startOfDay(),
            'week'  => now()->subWeek()->startOfDay(),
            default => now()->subMonth()->startOfDay(),
        };

        // --- CHART 1: Global Application Status ---
        $statusCounts = Loan::where('created_at', '>=', $startDate)
            ->selectRaw("
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
                SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected,
                SUM(CASE WHEN status = 'paid' THEN 1 ELSE 0 END) as paid
            ")
            ->first();

        $chart1Data = [
            'labels' => ['Pending', 'Approved', 'Rejected', 'Paid'],
            'data' => [
                $statusCounts->pending ?? 0,
                $statusCounts->approved ?? 0,
                $statusCounts->rejected ?? 0,
                $statusCounts->paid ?? 0
            ],
            'colors' => ['#FFCE56', '#36A2EB', '#FF6384', '#4BC0C0']
        ];

        // --- CHART 2: Loans by Category ---
        $typeData = Loan::where('loans.created_at', '>=', $startDate)
            ->join('loan_types', 'loans.loan_type_id', '=', 'loan_types.id')
            ->selectRaw('loan_types.name, count(*) as count')
            ->groupBy('loan_types.name')
            ->pluck('count', 'name')
            ->toArray();

        if (empty($typeData)) {
            $typeData = ['No Data' => 1];
        }

        $chart2Data = [
            'labels' => array_keys($typeData),
            'data' => array_values($typeData),
            'colors' => ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0']
        ];

        // --- CHART 3: Monthly Revenue Trend (PostgreSQL Compatible) ---
        // Use DB::raw with TO_CHAR for PostgreSQL compatibility
        // Fallback to strftime for SQLite via conditional logic
        $dbDriver = config('database.default');
        $dateExpr = $dbDriver === 'pgsql' 
            ? "TO_CHAR(created_at, 'YYYY-MM')" 
            : "strftime('%Y-%m', created_at)";

        $revenueData = Loan::where('status', 'approved')
            ->where('created_at', '>=', now()->subMonths(6))
            ->selectRaw("$dateExpr as month, SUM(total_repayable_amount) as revenue")
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->mapWithKeys(fn($item) => [$item->month => $item->revenue])
            ->toArray();

        // Fill missing months with 0
        $last6Months = collect(range(5, 0))->map(fn($i) => now()->subMonths($i)->format('Y-m'));
        $fullRevenueData = $last6Months->map(fn($m) => $revenueData[$m] ?? 0)->values()->toArray();
        $revenueLabels = $last6Months->toArray();

        $chart3Data = [
            'labels' => $revenueLabels,
            'data' => $fullRevenueData,
            'color' => '#0ea5e9'
        ];

        // --- CHART 4: Approval Rate Over Time (PostgreSQL Compatible) ---
        $approvalData = Loan::where('created_at', '>=', now()->subMonths(6))
            ->selectRaw("$dateExpr as month, 
                         COUNT(*) as total,
                         SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved")
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(fn($item) => $item->total > 0 ? round(($item->approved / $item->total) * 100, 1) : 0)
            ->values()
            ->toArray();

        $fullApprovalData = collect(range(5, 0))->map(function($i) use ($approvalData) {
            return $approvalData[5 - $i] ?? 0;
        })->toArray();

        $chart4Data = [
            'labels' => $revenueLabels,
            'data' => $fullApprovalData,
            'color' => '#10b981'
        ];

        return view('admin.analytics', compact(
            'chart1Data', 'chart2Data', 'chart3Data', 'chart4Data', 'filter'
        ));
    }
}