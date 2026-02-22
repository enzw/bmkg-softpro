<?php

namespace App\Http\Controllers;

use App\Models\Chatbot;
use App\Models\ServiceRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ChatbotRatingController extends Controller
{
    /**
     * Store a chatbot rating
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'session_id' => 'required|string',
            'rating' => 'required|integer|between:1,5',
            'review' => 'nullable|string|max:1000',
        ]);

        try {
            // Get or create chatbot record
            $chatbot = Chatbot::firstOrCreate(
                ['session_id' => $validated['session_id']],
                [
                    'id' => Str::uuid(),
                    'user_id' => Auth::id(),
                    'status' => 'completed'
                ]
            );

            // Create or update rating
            $rating = ServiceRating::updateOrCreate(
                [
                    'user_id' => Auth::user()?->id,
                    'rateable_id' => $chatbot->id,
                    'rateable_type' => Chatbot::class,
                ],
                [
                    'rating' => $validated['rating'],
                    'review' => $validated['review'] ?? null,
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Terima kasih atas rating Anda! Feedback Anda sangat membantu kami meningkatkan layanan.',
                'data' => $rating,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan rating. Silakan coba lagi.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get average chatbot rating
     */
    public function getAverageRating()
    {
        $average = ServiceRating::where('rateable_type', Chatbot::class)
            ->avg('rating') ?? 0;

        return response()->json([
            'success' => true,
            'average' => round($average, 1),
            'max' => 5,
        ]);
    }
}
