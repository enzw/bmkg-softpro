<?php

use App\Models\SewaAlat;
use App\Models\ServiceRating;
use App\Models\User;

$user = User::where('email', 'absasamsidan@gmail.com')->first();

if (!$user) {
    echo "User not found\n";
    exit;
}

echo "User ID: " . $user->id . "\n\n";

// Check all SewaAlat for this user
$sewaAlat = SewaAlat::where('user_id', $user->id)->get();

echo "Total SewaAlat: " . $sewaAlat->count() . "\n\n";

foreach ($sewaAlat as $item) {
    echo "SewaAlat ID: " . $item->id . "\n";
    echo "Status: [" . $item->status . "]\n";

    $rating = ServiceRating::where('rateable_id', $item->id)
        ->where('rateable_type', SewaAlat::class)
        ->first();

    echo "Has Rating: " . ($rating ? 'Yes (Rating ID: ' . $rating->id . ', Stars: ' . $rating->rating . ')' : 'No') . "\n";
    echo "---\n";
}

// Check if there's a pending rating using the same logic as controller
$completedStatuses = ['completed', 'selesai', 'Selesai', 'Dikembalikan'];

$pending = SewaAlat::where('user_id', $user->id)
    ->whereIn('status', $completedStatuses)
    ->doesntHave('rating')
    ->first();

echo "\nPending Rating Check:\n";
if ($pending) {
    echo "Found pending SewaAlat ID: " . $pending->id . " with status: " . $pending->status . "\n";
} else {
    echo "No pending SewaAlat found\n";
}
