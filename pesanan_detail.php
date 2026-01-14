<?php
$page_title = "Detail Pesanan";
require_once __DIR__ . '/includes/header.php';
require_login();

$u = current_user();
if (($u['role'] ?? '') === 'admin') { echo "<div class='notice'>Detail pesanan pelanggan ada di Admin > Kelola Pesanan.</div>"; require __DIR__ . '/includes/footer.php'; exit; }

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT id,status,total_amount,note,created_at FROM orders WHERE id=? AND user_id=? LIMIT 1");
$stmt->execute([$id, (int)$u['id']]);
$o = $stmt->fetch();
if(!$o){ echo "<div class='error'>Pesanan tidak ditemukan.</div>"; require __DIR__ . '/includes/footer.php'; exit; }

$labels = [
  'unpaid'=>'Menunggu Pembayaran',
  'processing'=>'Diproses',
  'done'=>'Selesai',
  'cancelled'=>'Dibatalkan',
  'returned'=>'Pengembalian',
];

$items = $pdo->prepare("SELECT oi.qty, oi.price, p.name,
  (SELECT image_path FROM product_images WHERE product_id=p.id ORDER BY id ASC LIMIT 1) img
  FROM order_items oi JOIN products p ON p.id=oi.product_id
  WHERE oi.order_id=?");
$items->execute([$id]);
$items = $items->fetchAll();

function actionBtn($href,$label,$primary=false,$confirm=null){
  $cls = $primary ? "btn primary" : "btn";
  $c = "";
  if ($confirm !== null && $confirm !== "") {
    $safe = str_replace("'", "\\'", $confirm);
    $c = " onclick=\"return confirm('".$safe."')\"";
  }
  $href = e($href);
  $label = e($label);
  return "<a class=\"{$cls}\" href=\"{$href}\"{$c}>{$label}</a>";
}
?>
<div class="section-head">
  <h2>Detail Pesanan #<?= (int)$o['id'] ?></h2>
  <div class="muted"><?= e($labels[$o['status']] ?? $o['status']) ?></div>
</div>

<div class="form-card" style="margin-top:12px">
  <div style="display:flex;justify-content:space-between;gap:10px;flex-wrap:wrap">
    <div>
      <div class="small">Tanggal</div>
      <div style="font-weight:1000"><?= e($o['created_at']) ?></div>
    </div>
    <div>
      <div class="small">Total</div>
      <div style="font-weight:1000;font-size:18px"><?= e(money_idr($o['total_amount'])) ?></div>
    </div>
  </div>
  <?php if($o['note']): ?>
    <div style="margin-top:10px" class="small">Catatan: <b><?= e($o['note']) ?></b></div>
  <?php endif; ?>

  <div style="margin-top:14px;display:flex;gap:10px;flex-wrap:wrap">
    <?php if($o['status']==='unpaid'): ?>
      <?= actionBtn("/Digipren/pembayaran.php?id=".$o['id'], "Bayar (QRIS)", true) ?>
      <?= actionBtn("/Digipren/order_action.php?action=cancel&id=".$o['id'], "Batalkan Pesanan", false, "Batalkan pesanan ini?") ?>
    <?php elseif($o['status']==='processing'): ?>
      <?= actionBtn("/Digipren/order_action.php?action=receive&id=".$o['id'], "Pesanan Diterima", true, "Konfirmasi pesanan sudah diterima?") ?>
      <?= actionBtn("/Digipren/order_action.php?action=cancel&id=".$o['id'], "Batalkan Pesanan", false, "Batalkan pesanan yang sedang diproses?") ?>
      <?= actionBtn("/Digipren/order_action.php?action=return&id=".$o['id'], "Ajukan Pengembalian", false, "Ajukan pengembalian?") ?>
    <?php elseif($o['status']==='done'): ?>
      <?= actionBtn("/Digipren/order_action.php?action=return&id=".$o['id'], "Ajukan Pengembalian", true, "Ajukan pengembalian?") ?>
    <?php endif; ?>
  </div>
</div>

<div style="height:12px"></div>

<div class="form-card">
  <div style="font-weight:1000;margin-bottom:10px">Item Pesanan</div>
  <?php foreach($items as $it): ?>
    <div style="display:flex;gap:12px;padding:10px 0;border-bottom:1px solid var(--line)">
      <div style="width:70px;height:70px;border-radius:16px;overflow:hidden;background:#f3f4f6">
        <?php if($it['img']): ?><img src="<?= e(img_url($it['img'])) ?>" style="width:100%;height:100%;object-fit:cover"><?php endif; ?>
      </div>
      <div style="flex:1">
        <div style="font-weight:1000"><?= e($it['name']) ?></div>
        <div class="small" style="margin-top:2px">Qty: <?= (int)$it['qty'] ?> • <?= e(money_idr($it['price'])) ?></div>
      </div>
      <div style="font-weight:1000"><?= e(money_idr($it['qty']*$it['price'])) ?></div>
    </div>
  <?php endforeach; ?>
</div>

<?php $active='orders'; require __DIR__ . '/includes/footer.php'; ?>
