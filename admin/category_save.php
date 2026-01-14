<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$id = (int)($_POST['id'] ?? 0);
$name = trim($_POST['name'] ?? '');
$slug = trim($_POST['slug'] ?? '');
if ($name===''){ header("Location: /Digipren/admin/categories.php"); exit; }
$slug = $slug==='' ? slugify($name) : slugify($slug);

if ($id>0){
  $stmt=$pdo->prepare("UPDATE categories SET name=?, slug=? WHERE id=?");
  $stmt->execute([$name,$slug,$id]);
} else {
  $stmt=$pdo->prepare("INSERT INTO categories (name,slug) VALUES (?,?)");
  $stmt->execute([$name,$slug]);
}
header("Location: /Digipren/admin/categories.php");
