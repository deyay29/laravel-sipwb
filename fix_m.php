<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\DB;
DB::table('migrations')->updateOrInsert(['migration' => '2026_10_29_053826_create_quiz_wisata_table'], ['batch' => 3]);
echo "ok";
