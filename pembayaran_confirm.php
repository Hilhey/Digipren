<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';
require_login();

$u = current_user();
$id = (int)($_POST['id'] ?? 0);

$stmt = $pdo->prepare("SELECT id,status FROM orders WHERE id=? AND user_id=? LIMIT 1");
$stmt->execute([$id, (int)$u['id']]);
$o = $stmt->fetch();
if(!$o){ http_response_code(404); echo "Order tidak ditemukan"; exit; }

if ($o['status'] !== 'unpaid') {
  header("Location: /Digipren/pesanan_detail.php?id=".$id);
  exit;
}

// Simulasi scan QR sukses -> status jadi processing
$pdo->prepare("UPDATE orders SET status='processing' WHERE id=?")->execute([$id]);

header("Location: /Digipren/pesanan_detail.php?id=".$id);
