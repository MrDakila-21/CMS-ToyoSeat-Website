<?php
// Dump raw file contents for inspection
$path = __DIR__ . '/../app/Models/Inquiry.php';
echo "--- FILE CONTENT START ---\n";
echo file_get_contents($path);
echo "\n--- FILE CONTENT END ---\n";

require $path;

echo "Declared classes containing 'Inquiry':\n";
foreach (get_declared_classes() as $c) {
    if (strpos($c, 'Inquiry') !== false) {
        echo $c . "\n";
    }
}
