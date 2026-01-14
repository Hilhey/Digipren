<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin();
$id = (int)($_GET['id'] ?? 0);
if($id>0){ $pdo->prepare("DELETE FROM categories WHERE id=?")->execute([$id]); }
header("Location: /Digipren/admin/categories.php");
