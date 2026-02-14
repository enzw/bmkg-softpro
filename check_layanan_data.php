<?php

use App\Models\LayananData;
use App\Models\User;

$user = User::where('email', 'absasamsidan@gmail.com')->first();

if (!$user) {
    echo "User not found\n";
    exit;
}

echo "User ID: " . $user->id . "\n";
echo "User Name: " . $user->name . "\n\n";

$layananData = LayananData::where('user_id', $user->id)->get();

echo "Total LayananData records: " . $layananData->count() . "\n\n";

foreach ($layananData as $item) {
    echo "LayananData ID: " . $item->id . "\n";
    echo "Status: [" . $item->status . "]\n";
    echo "Status length: " . strlen($item->status) . "\n";
    echo "Has Rating: " . ($item->rating ? 'Yes (ID: ' . $item->rating->id . ')' : 'No') . "\n";
    echo "---\n";
}
