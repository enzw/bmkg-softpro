<?php

use App\Models\Kunjungan;
use App\Models\User;

$user = User::where('email', 'absasamsidan@gmail.com')->first();

if (!$user) {
    echo "User not found\n";
    exit;
}

echo "User ID: " . $user->id . "\n\n";

$kunjungan = Kunjungan::where('user_id', $user->id)->get();

echo "Total Kunjungan records: " . $kunjungan->count() . "\n\n";

foreach ($kunjungan as $item) {
    echo "Kunjungan ID: " . $item->id . "\n";
    echo "Status: [" . $item->status . "]\n";
    echo "Status length: " . strlen($item->status) . "\n";
    echo "Has Rating: " . ($item->rating ? 'Yes' : 'No') . "\n";
    echo "---\n";
}
