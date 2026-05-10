<?php

namespace App\Http\Controllers;

use App\Services\ForecastService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForecastController extends Controller
{
    protected $forecastService;

    public function __construct(ForecastService $forecastService)
    {
        $this->forecastService = $forecastService;
    }

    public function index()
    {
        $user = Auth::user();
        $overview = $this->forecastService->getWealthOverview($user);
        $projections = $this->forecastService->projectSavings($user, 12);
        
        $goals = $user->savingsGoals()->where('status', 'active')->get()->map(function($goal) {
            $goal->estimated_date = $this->forecastService->estimateGoalCompletion($goal);
            return $goal;
        });

        return view('reports.forecast', compact('overview', 'projections', 'goals'));
    }
}
