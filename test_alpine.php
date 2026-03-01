<?php
$name = "Macbook 'Pro'";
echo "data: { name: '" . addslashes($name) . "' }\n";
echo "data: { name: " . json_encode($name) . " }\n";
