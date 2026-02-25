<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$str = "Test ' String \" Quotes";
echo "Blade js directive output: " . \Illuminate\Support\Js::from($str)->toHtml() . "\n";
