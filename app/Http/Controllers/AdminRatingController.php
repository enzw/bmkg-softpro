<?php

namespace App\Http\Controllers;

use App\Models\ServiceRating;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminRatingController extends Controller
{
    public function index()
    {
        $ratings = ServiceRating::with('user', 'rateable')->latest()->take(10)->get();

        // Calculate average rating
        $averageRating = ServiceRating::avg('rating') ?? 0;
        $totalRatings = ServiceRating::count();

        // Rating distribution for stars
        $distribution = ServiceRating::select('rating', DB::raw('count(*) as total'))
            ->groupBy('rating')
            ->orderBy('rating', 'desc')
            ->get()
            ->pluck('total', 'rating')
            ->toArray();

        // Ensure all stars 1-5 exist in distribution
        for ($i = 1; $i <= 5; $i++) {
            if (!isset($distribution[$i])) {
                $distribution[$i] = 0;
            }
        }

        // Calculate NET PROMOTER Score (NPS)
        // Promoters: rating 4-5, Detractors: rating 1-2
        $promoters = ($distribution[5] ?? 0) + ($distribution[4] ?? 0);
        $detractors = ($distribution[1] ?? 0) + ($distribution[2] ?? 0);
        $netPromoterScore = $totalRatings > 0 ? round((($promoters - $detractors) / $totalRatings) * 100) : 0;

        // Calculate monthly target progress
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $daysInMonth = $endOfMonth->day;
        $currentDay = Carbon::now()->day;
        $expectedRatings = round($daysInMonth / $currentDay) * $totalRatings;
        $monthlyTargetProgress = min(round(($totalRatings / max($expectedRatings, 1)) * 100), 100);

        return view('pages.admin.ratings.index', [
            'title' => 'Rating',
            'ratings' => $ratings,
            'averageRating' => round($averageRating, 1),
            'totalRatings' => $totalRatings,
            'distribution' => $distribution,
            'netPromoterScore' => $netPromoterScore,
            'monthlyTargetProgress' => $monthlyTargetProgress,
        ]);
    }

    public function getRatingChartData(Request $request)
    {
        $days = $request->query('days', 30);

        $results = ServiceRating::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('ROUND(AVG(rating), 1) as average')
        )
            ->where('created_at', '>=', Carbon::now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return response()->json([
            'labels' => $results->pluck('date'),
            'values' => $results->pluck('average'),
        ]);
    }
}
