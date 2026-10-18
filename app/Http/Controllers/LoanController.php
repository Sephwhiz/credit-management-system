<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\LoanService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class LoanController extends Controller
{
    protected $loanService;

    public function __construct(LoanService $loanService)
    {
        $this->loanService = $loanService;
    }

    /**
     * Display a listing of the user's loans.
     */
    public function index(): View
    {
        $loans = $this->loanService->getUserLoans();
        return view('loans.index', compact('loans'));
    }

    /**
     * Show the form for creating a new loan.
     */
    public function create(): View
    {
        $loanTypes = \App\Models\LoanType::all();
        return view('loans.create', compact('loanTypes'));
    }

    /**
     * Store a newly created loan in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'loan_type_id' => 'required|exists:loan_types,id',
            'amount'       => 'required|numeric|min:100',
            'purpose'      => 'nullable|string|max:500',
            'due_date'     => 'nullable|date|after:today',
        ]);

        $this->loanService->applyForLoan($validated);

        return redirect()->route('loans.index')
            ->with('success', 'Loan application submitted successfully!');
    }

    /**
     * Display the specified loan.
     */
    public function show(int $id): View
    {
        // To be implemented later
        return view('loans.show');
    }

    /**
     * Show the form for editing the specified loan.
     */
    public function edit(int $id): View
    {
        // To be implemented later
        return view('loans.edit');
    }

    /**
     * Update the specified loan in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        // To be implemented later
        return redirect()->route('loans.index');
    }

    /**
     * Remove the specified loan from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        // To be implemented later
        return redirect()->route('loans.index');
    }
}