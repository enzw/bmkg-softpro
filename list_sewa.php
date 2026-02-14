<?php

use App\Models\SewaAlat;

$all = SewaAlat::all();

echo "Total SewaAlat records: " . $all->count() . "\n\n";

foreach ($all as $s) {
    echo "ID: " . $s->id . "\n";
    echo "User ID: " . $s->user_id . "\n";
    echo "Status: [" . $s->status . "]\n";
    echo "Status length: " . strlen($s->status) . "\n";
    echo "---\n";
}
