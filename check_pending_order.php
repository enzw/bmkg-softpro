<?php

use App\Models\SewaAlat;
use App\Models\Magang;
use App\Models\Asuransi;
use App\Models\Kunjungan;
use App\Models\JasaKonsultasi;
use App\Models\Survey;
use App\Models\LayananData;
use App\Models\User;

$user = User::where('email', 'absasamsidan@gmail.com')->first();
$id = $user->id;
$statuses = ['completed', 'selesai', 'Selesai', 'Dikembalikan'];

echo "Checking pending services for user: $id\n\n";

// 1. SewaAlat
$sewa = SewaAlat::where('user_id', $id)->whereIn('status', $statuses)->doesntHave('rating')->first();
echo "1. SewaAlat: " . ($sewa ? "PENDING (ID: {$sewa->id})" : "None") . "\n";

// 2. Magang
$magang = Magang::where('user_id', $id)->whereIn('status', $statuses)->doesntHave('rating')->first();
echo "2. Magang: " . ($magang ? "PENDING (ID: {$magang->id})" : "None") . "\n";

// 3. Asuransi
$asuransi = Asuransi::where('user_id', $id)->whereIn('status', $statuses)->doesntHave('rating')->first();
echo "3. Asuransi: " . ($asuransi ? "PENDING (ID: {$asuransi->id})" : "None") . "\n";

// 4. Kunjungan
$kunjungan = Kunjungan::where('user_id', $id)->whereIn('status', $statuses)->doesntHave('rating')->first();
echo "4. Kunjungan: " . ($kunjungan ? "PENDING (ID: {$kunjungan->id})" : "None") . "\n";

// Other services...
