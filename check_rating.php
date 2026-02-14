<?php

use App\Models\SewaAlat;
use App\Models\ServiceRating;

$sewa = SewaAlat::where('status', 'dikembalikan')->first();

if ($sewa) {
    echo "SewaAlat ID: " . $sewa->id . "\n";
    echo "User ID: " . $sewa->user_id . "\n";
    echo "Status: " . $sewa->status . "\n";

    $rating = ServiceRating::where('rateable_id', $sewa->id)
        ->where('rateable_type', SewaAlat::class)
        ->first();

    echo "Has Rating: " . ($rating ? 'Yes (Rating ID: ' . $rating->id . ')' : 'No') . "\n";

    if ($rating) {
        echo "Rating Value: " . $rating->rating . "\n";
        echo "Review: " . ($rating->review ?? 'No review') . "\n";
    }
} else {
    echo "No SewaAlat with status 'dikembalikan' found\n";
}
