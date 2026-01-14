<?php
// media.php - serve uploads via PHP (bypass Apache static issues)
$rel = $_GET['f'] ?? '';
$rel = str_replace('\\', '/', $rel);
$rel = ltrim($rel, '/');

if (!preg_match('~^uploads/products/[a-zA-Z0-9._-]+$~', $rel)) {
  http_response_code(404);
  exit('Not Found');
}

$path = __DIR__ . '/' . $rel;
if (!is_file($path)) {
  http_response_code(404);
  exit('Not Found');
}

$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
$types = [
  'jpg'=>'image/jpeg','jpeg'=>'image/jpeg','png'=>'image/png','webp'=>'image/webp','gif'=>'image/gif'
];

header('Content-Type: ' . ($types[$ext] ?? 'application/octet-stream'));
header('Content-Length: ' . filesize($path));
readfile($path);
exit;
