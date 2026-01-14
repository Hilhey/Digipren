<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
$u = current_user();

$stmt = $pdo->prepare("DELETE ci FROM cart_items ci
  JOIN carts c ON c.id=ci.cart_id
  WHERE ci.id=? AND c.user_id=?");
$stmt->execute([$id, (int)$u['id']]);
header("Location: /Digipren/keranjang.php");
