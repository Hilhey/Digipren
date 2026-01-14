<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';
require_login();

$item_id = (int)($_POST['item_id'] ?? 0);
$qty = max(1, (int)($_POST['qty'] ?? 1));
$u = current_user();

$stmt = $pdo->prepare("SELECT ci.id, p.stock
  FROM cart_items ci
  JOIN carts c ON c.id=ci.cart_id
  JOIN products p ON p.id=ci.product_id
  WHERE ci.id=? AND c.user_id=? LIMIT 1");
$stmt->execute([$item_id, (int)$u['id']]);
$row = $stmt->fetch();
if (!$row) { header("Location: /Digipren/keranjang.php"); exit; }

if ($qty > (int)$row['stock']) $qty = (int)$row['stock'];
$stmt = $pdo->prepare("UPDATE cart_items SET qty=? WHERE id=?");
$stmt->execute([$qty, $item_id]);
header("Location: /Digipren/keranjang.php");
