<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';
require_login();
$u = current_user();
if (($u['role'] ?? '') === 'admin') { echo "Admin tidak bisa checkout."; exit; }

$note = trim($_POST['note'] ?? '');
$payment_method = $_POST['payment_method'] ?? 'qris';

$pdo->beginTransaction();
try{
  $stmt = $pdo->prepare("SELECT id FROM carts WHERE user_id=? LIMIT 1");
  $stmt->execute([(int)$u['id']]);
  $cart = $stmt->fetch();
  if(!$cart) throw new Exception("Cart kosong");

  $stmt = $pdo->prepare("SELECT ci.product_id, ci.qty, p.price
    FROM cart_items ci JOIN products p ON p.id=ci.product_id
    WHERE ci.cart_id=?");
  $stmt->execute([(int)$cart['id']]);
  $items = $stmt->fetchAll();
  if(!$items) throw new Exception("Cart kosong");

  $total = 0;
  foreach($items as $it){ $total += $it['price']*$it['qty']; }

  // status awal: unpaid (menunggu pembayaran)
  $stmt = $pdo->prepare("INSERT INTO orders (user_id, status, total_amount, note) VALUES (?,?,?,?)");
  $stmt->execute([(int)$u['id'], 'unpaid', $total, $note]);
  $order_id = (int)$pdo->lastInsertId();

  $stmtItem = $pdo->prepare("INSERT INTO order_items (order_id, product_id, qty, price) VALUES (?,?,?,?)");
  foreach($items as $it){
    $stmtItem->execute([$order_id, (int)$it['product_id'], (int)$it['qty'], (float)$it['price']]);
  }

  $pdo->prepare("DELETE FROM cart_items WHERE cart_id=?")->execute([(int)$cart['id']]);
  $pdo->commit();

  // ke halaman pembayaran (QRIS simulasi)
  header("Location: /Digipren/pembayaran.php?id=".$order_id);
  exit;
}catch(Throwable $e){
  $pdo->rollBack();
  echo "Checkout gagal: " . e($e->getMessage());
}
