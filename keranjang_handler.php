<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';
require_login();

$u = current_user();
$product_id = (int)($_POST['product_id'] ?? 0);
$qty = max(1, (int)($_POST['qty'] ?? 1));

$stmt = $pdo->prepare("SELECT id, stock FROM products WHERE id=? AND is_active=1 LIMIT 1");
$stmt->execute([$product_id]);
$p = $stmt->fetch();
if (!$p) { header("Location: /Digipren/index.php"); exit; }
if ($qty > (int)$p['stock']) $qty = (int)$p['stock'];

$stmt = $pdo->prepare("SELECT id FROM carts WHERE user_id=? LIMIT 1");
$stmt->execute([(int)$u['id']]);
$cart = $stmt->fetch();
if (!$cart) {
  $stmt = $pdo->prepare("INSERT INTO carts (user_id) VALUES (?)");
  $stmt->execute([(int)$u['id']]);
  $cart_id = (int)$pdo->lastInsertId();
} else $cart_id = (int)$cart['id'];

$stmt = $pdo->prepare("SELECT id, qty FROM cart_items WHERE cart_id=? AND product_id=? LIMIT 1");
$stmt->execute([$cart_id, $product_id]);
$it = $stmt->fetch();
if ($it) {
  $newqty = min((int)$p['stock'], (int)$it['qty'] + $qty);
  $stmt = $pdo->prepare("UPDATE cart_items SET qty=? WHERE id=?");
  $stmt->execute([$newqty, (int)$it['id']]);
} else {
  $stmt = $pdo->prepare("INSERT INTO cart_items (cart_id, product_id, qty) VALUES (?,?,?)");
  $stmt->execute([$cart_id, $product_id, $qty]);
}
header("Location: /Digipren/keranjang.php");
