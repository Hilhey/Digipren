<?php
require_once __DIR__ . '/includes/header.php';
require_login();
$u = current_user();

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT id, status, total_amount, note, created_at FROM orders WHERE id=? AND user_id=? LIMIT 1");
$stmt->execute([$id, (int)$u['id']]);
$o = $stmt->fetch();
if(!$o){ echo "<div class='error'>Pesanan tidak ditemukan.</div>"; require __DIR__ . '/includes/footer.php'; exit; }

$labels = [
  'unpaid'=>'Belum Dibayar',
  'processing'=>'Diproses',
  'done'=>'Selesai',
  'cancelled'=>'Dibatalkan',
  'returned'=>'Pengembalian',
];
function badge_class($s){
  return [
    'unpaid'=>'badge-unpaid',
    'processing'=>'badge-processing',
    'done'=>'badge-done',
    'cancelled'=>'badge-cancelled',
    'returned'=>'badge-returned',
  ][$s] ?? 'badge-unpaid';
}

$stmt = $pdo->prepare("SELECT oi.qty, oi.price, p.name,
  (SELECT image_path FROM product_images WHERE product_id=p.id ORDER BY id ASC LIMIT 1) img
  FROM order_items oi JOIN products p ON p.id=oi.product_id
  WHERE oi.order_id=?");
$stmt->execute([$id]);
$items = $stmt->fetchAll();
?>
<div style="display:flex;justify-content:space-between;align-items:flex-start;gap:10px;flex-wrap:wrap">
  <div>
    <h2 style="margin:0;font-size:18px">Detail Pesanan #<?= (int)$o['id'] ?></h2>
    <div class="small" style="margin-top:6px">Tanggal: <?= e($o['created_at']) ?></div>
  </div>
  <div class="badge-pill <?= e(badge_class($o['status'])) ?>"><?= e($labels[$o['status']] ?? $o['status']) ?></div>
</div>

<div style="height:12px"></div>

<div class="form-card">
  <?php foreach($items as $it): ?>
    <div style="display:flex;gap:12px;padding:10px 0;border-bottom:1px solid var(--line);align-items:center">
      <div style="width:64px;height:64px;border-radius:14px;overflow:hidden;background:#f3f4f6;border:1px solid var(--line)">
        <?php if($it['img']): ?><img src="<?= e(img_url($it['img'])) ?>" style="width:100%;height:100%;object-fit:cover"><?php endif; ?>
      </div>
      <div style="flex:1">
        <div style="font-weight:1000"><?= e($it['name']) ?></div>
        <div class="small">Qty: <?= (int)$it['qty'] ?> • <?= e(money_idr($it['price'])) ?></div>
      </div>
      <div style="font-weight:1000"><?= e(money_idr($it['price'] * $it['qty'])) ?></div>
    </div>
  <?php endforeach; ?>

  <div style="display:flex;justify-content:space-between;align-items:center;margin-top:12px">
    <div style="font-weight:1000">Total</div>
    <div style="font-weight:1000;font-size:18px"><?= e(money_idr($o['total_amount'])) ?></div>
  </div>

  <?php if(!empty($o['note'])): ?>
    <div style="margin-top:12px" class="notice">Catatan: <?= e($o['note']) ?></div>
  <?php endif; ?>

  <a class="btn" href="/Digipren/pesanan.php" style="margin-top:12px;width:100%">Kembali ke Pesanan Saya</a>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
