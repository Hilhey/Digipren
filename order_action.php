<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';
require_login();

$u = current_user();
if (($u['role'] ?? '') === 'admin') { http_response_code(403); echo "Admin tidak boleh aksi ini."; exit; }

$action = $_GET['action'] ?? '';
$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT id,status FROM orders WHERE id=? AND user_id=? LIMIT 1");
$stmt->execute([$id, (int)$u['id']]);
$o = $stmt->fetch();
if(!$o){ http_response_code(404); echo "Order tidak ditemukan"; exit; }

$status = $o['status'];

if ($action === 'cancel') {
  if ($status === 'unpaid' || $status === 'processing') {
    $pdo->prepare("UPDATE orders SET status='cancelled' WHERE id=?")->execute([$id]);
  }
  header("Location: /Digipren/pesanan_detail.php?id=".$id);
  exit;
}

if ($action === 'receive') {
  // hanya jika sudah dibayar dan diproses
  if ($status === 'processing') {
    $pdo->prepare("UPDATE orders SET status='done' WHERE id=?")->execute([$id]);
  }
  header("Location: /Digipren/pesanan_detail.php?id=".$id);
  exit;
}

if ($action === 'return') {
  // return jika sedang diproses atau sudah selesai
  if ($status === 'processing' || $status === 'done') {
    $pdo->prepare("UPDATE orders SET status='returned' WHERE id=?")->execute([$id]);
  }
  header("Location: /Digipren/pesanan_detail.php?id=".$id);
  exit;
}

header("Location: /Digipren/pesanan_detail.php?id=".$id);
