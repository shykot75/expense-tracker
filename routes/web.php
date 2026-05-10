<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecurringBillController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SavingsGoalController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);

    // Google OAuth Routes
    Route::get('auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);

    Route::get('register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
});

Route::middleware(['auth', 'no-cache'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('onboarding', [OnboardingController::class, 'index'])->name('onboarding');
    Route::post('onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');

    Route::get('expenses', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::get('expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
    Route::post('expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::get('expenses/{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit');
    Route::put('expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
    Route::delete('expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');
    Route::post('categories/quick-add', [ExpenseController::class, 'addCategory'])->name('categories.quick-add');

    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Settings Hub
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings/budget', [SettingsController::class, 'updateBudget'])->name('settings.updateBudget');
    Route::put('settings/categories/{category}', [SettingsController::class, 'updateCategory'])->name('settings.updateCategory');
    Route::delete('settings/categories/{category}', [SettingsController::class, 'deleteCategory'])->name('settings.deleteCategory');
    Route::post('settings/reset', [SettingsController::class, 'resetData'])->name('settings.reset');

    // Loans Tracker
    Route::get('loans', [LoanController::class, 'index'])->name('loans.index');
    Route::get('loans/create', [LoanController::class, 'create'])->name('loans.create');
    Route::post('loans', [LoanController::class, 'store'])->name('loans.store');
    Route::get('loans/{loan}/edit', [LoanController::class, 'edit'])->name('loans.edit');
    Route::put('loans/{loan}', [LoanController::class, 'update'])->name('loans.update');
    Route::post('loans/{loan}/toggle', [LoanController::class, 'toggleStatus'])->name('loans.toggle');
    Route::delete('loans/{loan}', [LoanController::class, 'destroy'])->name('loans.destroy');

    Route::post('recurring-bills/{recurring_bill}/toggle', [RecurringBillController::class, 'toggleStatus'])->name('recurring-bills.toggle');
    Route::resource('recurring-bills', RecurringBillController::class);

    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/download', [ReportController::class, 'downloadPdf'])->name('reports.download');

    // Savings Goals
    Route::post('savings-goals', [SavingsGoalController::class, 'store'])->name('savings-goals.store');
    Route::put('savings-goals/{goal}', [SavingsGoalController::class, 'update'])->name('savings-goals.update');
    Route::post('savings-goals/{goal}/contribute', [SavingsGoalController::class, 'contribute'])->name('savings-goals.contribute');
    Route::delete('savings-goals/{goal}', [SavingsGoalController::class, 'destroy'])->name('savings-goals.destroy');

    // Wealth Forecast
    Route::get('analytics/forecast', [\App\Http\Controllers\ForecastController::class, 'index'])->name('analytics.forecast');
});
