<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Http\Kernel::class);

use DB;

$updated = \Illuminate\Support\Facades\DB::table('sewa_alats')
    ->where('status', 'Menunggu')
    ->update(['status' => 'Belum Lunas']);

echo "Total records updated: " . $updated . "\n";
