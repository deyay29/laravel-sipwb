<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

DB::table('migrations')->updateOrInsert(['migration' => '2026_10_29_053826_create_quiz_wisata_table'], ['batch' => 3]);
echo 'ok';
