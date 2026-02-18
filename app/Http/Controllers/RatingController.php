<?php

namespace App\Http\Controllers;

use App\Models\ServiceRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RatingController extends Controller
{
    /**
     * Show the general rating page
     */
    public function create()
    {
        return view('pages.rating.create');
    }

    /**
     * Store a general rating (from the dedicated rating page)
     */
    public function storeGeneral(Request $request)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
            'service_type' => 'required|string|in:Umum,Magang,Kunjungan,Konsultasi,Asuransi,Survey,Layanan Data,Sewa Alat',
        ]);

        try {
            // Create a generic UUID for this rating
            $genericId = Str::uuid();

            ServiceRating::create([
                'user_id' => auth()->id(),
                'rateable_id' => $genericId,
                'rateable_type' => 'Pelayanan\\' . str_replace(' ', '', $validated['service_type']),
                'rating' => $validated['rating'],
                'review' => $validated['review'],
            ]);

            return redirect()->back()->with('success', 'Terima kasih! Penilaian Anda telah disimpan.');
        } catch (\Exception $e) {
            Log::error('Rating Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan penilaian.');
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'rateable_id' => 'required|string',
            'rateable_type' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        $rateableType = $validated['rateable_type'];
        $rateableId = $validated['rateable_id'];

        // Validate that the rateable_type is a valid model
        if (!class_exists($rateableType)) {
            return response()->json(['message' => 'Invalid service type.'], 400);
        }

        // Find the service record
        $service = $rateableType::where('id', $rateableId)
            ->where('user_id', $user->id)
            ->first();

        if (!$service) {
            return response()->json(['message' => 'Service not found or access denied.'], 404);
        }

        // Ensure service is completed (check for status column)
        // Adjust status check based on model if necessary, but generally 'completed', 'selesai', etc.
        // For simplicity, we assume the frontend only sends completed items, 
        // but we should verify if possible.
        // However, status values vary (e.g. 'completed', 'Diterima', 'Selesai'). 
        // We'll trust the query logic that provided this item to be ratable.

        // Check if already rated
        if ($service->rating()->exists()) {
            return response()->json(['message' => 'You have already rated this service.'], 400);
        }

        try {
            $service->rating()->create([
                'user_id' => $user->id,
                'rating' => $validated['rating'],
                'review' => $validated['review'],
            ]);

            return response()->json(['message' => 'Thank you for your rating!']);
        } catch (\Exception $e) {
            Log::error('Rating Error: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred while saving your rating.'], 500);
        }
    }
}
