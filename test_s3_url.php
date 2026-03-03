<?php

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Simulate production config from .env.koyeb
Config::set('filesystems.disks.s3.key', '5259328fcfe520430b313e0d6473a329');
Config::set('filesystems.disks.s3.secret', 'd2088cca9b665a7ebae8b04dd844a3036026e35fc3bdd42d6e8da4fdd7fa9469');
Config::set('filesystems.disks.s3.region', 'auto');
Config::set('filesystems.disks.s3.bucket', 'bmkg');
Config::set('filesystems.disks.s3.endpoint', 'https://c0239ec19f4a67091c337d8489b1a62f.r2.cloudflarestorage.com');
Config::set('filesystems.disks.s3.use_path_style_endpoint', true);

$filePath = 'permohonan/sewa-alat/69a69b8a38241_1772526474.jpg';
try {
    // Note: temporaryUrl might fail if league/flysystem-aws-s3-v3 is not installed or configured correctly
    // But we are just testing what it *returns*
    $url = Storage::disk('s3')->temporaryUrl($filePath, now()->addMinutes(60));
    echo "GENERATED_URL: " . $url . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
