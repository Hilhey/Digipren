<?php
$dir = __DIR__ . '/../uploads/products';
echo "<pre>";
echo "DIR: $dir
";
echo "Exists: " . (is_dir($dir) ? "YES" : "NO") . "
";
echo "Writable: " . (is_writable($dir) ? "YES" : "NO") . "

";
if (!is_dir($dir)) { echo "Folder belum ada.
"; exit; }
$files = glob($dir . '/*.*');
echo "Files count: " . count($files) . "
";
foreach ($files as $f) echo basename($f) . "
";
echo "</pre>";
