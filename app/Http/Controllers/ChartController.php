<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChartController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->input('filter', 'month');
        
        $startDate = match($filter) {
            'today' => now()->startOfDay(),
            'week'  => now()->subWeek()->startOfDay(),
            default => now()->subMonth()->startOfDay(),
        };

        // --- DATA FOR CHART 1: Application Status ---
        $statusCounts = Loan::where('loans.created_at', '>=', $startDate)
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

        // --- DATA FOR CHART 2: Loans by Category ---
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

        return view('charts.index', compact('chart1Data', 'chart2Data', 'filter'));
    }
}