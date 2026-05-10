<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class LoanController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $loans = Auth::user()->loans()->latest()->get();
        $totalLent = $loans->where('loan_type', 'lent')->where('status', 'active')->sum('amount');
        $totalBorrowed = $loans->where('loan_type', 'borrowed')->where('status', 'active')->sum('amount');
        
        $totalSettledLent = $loans->where('loan_type', 'lent')->where('status', 'paid')->sum('amount');
        $totalSettledBorrowed = $loans->where('loan_type', 'borrowed')->where('status', 'paid')->sum('amount');

        return view('loans.index', compact('loans', 'totalLent', 'totalBorrowed', 'totalSettledLent', 'totalSettledBorrowed'));
    }

    public function create()
    {
        return view('loans.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'loan_type' => ['required', 'in:lent,borrowed'],
                'person_name' => ['required', 'string', 'max:255'],
                'amount' => ['required', 'numeric', 'min:0'],
                'loan_date' => ['required', 'date'],
                'deadline_date' => ['nullable', 'date', 'after_or_equal:loan_date'],
                'description' => ['nullable', 'string'],
            ]);

            Auth::user()->loans()->create($validated + ['status' => 'active']);
            return redirect()->route('loans.index')->with('success', 'New loan record established successfully.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Loan Store Error: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong while creating the loan record. Please try again.')->withInput();
        }
    }

    public function edit(Loan $loan)
    {
        $this->authorize('update', $loan);
        return view('loans.edit', compact('loan'));
    }

    public function update(Request $request, Loan $loan)
    {
        $this->authorize('update', $loan);

        try {
            $validated = $request->validate([
                'loan_type' => ['required', 'in:lent,borrowed'],
                'person_name' => ['required', 'string', 'max:255'],
                'amount' => ['required', 'numeric', 'min:0'],
                'loan_date' => ['required', 'date'],
                'deadline_date' => ['nullable', 'date', 'after_or_equal:loan_date'],
                'description' => ['nullable', 'string'],
                'status' => ['required', 'in:active,paid'],
            ]);

            $loan->update($validated);
            return redirect()->route('loans.index')->with('success', 'Loan record updated perfectly.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Loan Update Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to update loan record. Please check your inputs.')->withInput();
        }
    }

    public function toggleStatus(Loan $loan, \App\Services\GamificationService $gamification)
    {
        $this->authorize('update', $loan);
        
        try {
            $newStatus = $loan->status === 'active' ? 'paid' : 'active';
            $loan->update(['status' => $newStatus]);

            if ($newStatus === 'paid') {
                $newBadges = $gamification->checkBadges(Auth::user());
                if (!empty($newBadges)) {
                    $badgeNames = array_map(fn($k) => \App\Services\GamificationService::getBadgeDetails($k)['name'], $newBadges);
                    return back()->with('success', 'Loan settled! 🏆 Achievement Unlocked: ' . implode(', ', $badgeNames));
                }
            }

            return back()->with('success', 'Loan status updated to ' . ucfirst($newStatus));
        } catch (\Exception $e) {
            \Log::error('Loan Toggle Error: ' . $e->getMessage());
            return back()->with('error', 'Could not update loan status at this moment.');
        }
    }

    public function destroy(Loan $loan)
    {
        $this->authorize('delete', $loan);
        try {
            $loan->delete();
            return redirect()->route('loans.index')->with('success', 'Loan record removed permanently.');
        } catch (\Exception $e) {
            \Log::error('Loan Delete Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to remove the loan record.');
        }
    }
}
