<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Income;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', date('n'));
        $year = $request->get('year', date('Y'));
        
        $date = Carbon::create($year, $month, 1);
        
        $user = Auth::user();

        // Data for the specific month
        $monthlyExpenses = $user->expenses()
            ->whereMonth('expense_date', $month)
            ->whereYear('expense_date', $year)
            ->with('category')
            ->get();

        $monthlyIncomes = $user->incomes()
            ->whereMonth('income_date', $month)
            ->whereYear('income_date', $year)
            ->get();

        $categorySummary = $monthlyExpenses->groupBy('category.name')
            ->map(fn($group) => $group->sum('amount'));

        $totalExpense = $monthlyExpenses->sum('amount');
        $totalIncome = $monthlyIncomes->sum('amount');
        $savings = $totalIncome - $totalExpense;

        // Loans data
        $activeLoans = $user->loans()->where('status', 'active')->get();
        $totalLent = $activeLoans->where('loan_type', 'lent')->sum('amount');
        $totalBorrowed = $activeLoans->where('loan_type', 'borrowed')->sum('amount');

        return view('reports.index', compact(
            'date', 'categorySummary', 'totalExpense', 'totalIncome', 
            'savings', 'totalLent', 'totalBorrowed', 'monthlyExpenses'
        ));
    }

    public function downloadPdf(Request $request)
    {
        $month = $request->get('month', date('n'));
        $year = $request->get('year', date('Y'));
        $date = Carbon::create($year, $month, 1);
        $user = Auth::user();

        $monthlyExpenses = $user->expenses()
            ->whereMonth('expense_date', $month)
            ->whereYear('expense_date', $year)
            ->with('category')
            ->get();

        $monthlyIncomes = $user->incomes()
            ->whereMonth('income_date', $month)
            ->whereYear('income_date', $year)
            ->get();

        $totalExpense = $monthlyExpenses->sum('amount');
        $totalIncome = $monthlyIncomes->sum('amount');
        
        $categorySummary = $monthlyExpenses->groupBy('category.name')
            ->map(fn($group) => $group->sum('amount'));

        $pdf = Pdf::loadView('reports.pdf', [
            'user' => $user,
            'date' => $date,
            'monthlyExpenses' => $monthlyExpenses,
            'categorySummary' => $categorySummary,
            'totalExpense' => $totalExpense,
            'totalIncome' => $totalIncome,
            'savings' => $totalIncome - $totalExpense
        ]);

        return $pdf->download("Financial_Report_{$date->format('M_Y')}.pdf");
    }
}
