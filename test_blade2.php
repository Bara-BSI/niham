<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$name = "Server Alpha";
echo "Output: " . \Illuminate\Support\Js::from($name)->toHtml() . "\n";
