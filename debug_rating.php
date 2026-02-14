<?php

use App\Models\SewaAlat;
use App\Models\User;
use App\Models\ServiceRating;

$user = User::where('email', 'absasamsidan@gmail.com')->first();
if (!$user) {
    echo "User not found\n";
    exit;
}

echo "User ID: " . $user->id . "\n";
echo "User Name: " . $user->name . "\n\n";

$sewaAlat = SewaAlat::where('user_id', $user->id)->get();
echo "Total SewaAlat records: " . $sewaAlat->count() . "\n\n";

foreach ($sewaAlat as $item) {
    echo "SewaAlat ID: " . $item->id . "\n";
    echo "Status: " . $item->status . "\n";
    echo "Has Rating: " . ($item->rating ? 'Yes (ID: ' . $item->rating->id . ')' : 'No') . "\n";
    echo "---\n";
}

// Test the getPendingRating logic
$completedStatuses = ['completed', 'selesai'];
$pending = SewaAlat::where('user_id', $user->id)
    ->whereIn('status', array_merge($completedStatuses, ['dikembalikan']))
    ->doesntHave('rating')
    ->first();

echo "\nPending Rating Check:\n";
if ($pending) {
    echo "Found pending SewaAlat ID: " . $pending->id . " with status: " . $pending->status . "\n";
} else {
    echo "No pending rating found\n";
}
