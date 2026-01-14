<?php
$page_title="Pesanan Saya";
require_once __DIR__ . '/includes/header.php';
require_login();
$u = current_user();

if (($u['role'] ?? '') === 'admin') { echo "<div class='notice'>Akun admin tidak memiliki pesanan. Kelola pesanan pelanggan di Admin.</div>"; require __DIR__ . '/includes/footer.php'; exit; }

$tab = $_GET['tab'] ?? 'unpaid';
$allowed = ['unpaid','processing','done','cancelled','returned'];
if (!in_array($tab, $allowed, true)) $tab = 'unpaid';

$stmt = $pdo->prepare("SELECT id, status, total_amount, created_at FROM orders WHERE user_id=? AND status=? ORDER BY id DESC");
$stmt->execute([(int)$u['id'], $tab]);
$orders = $stmt->fetchAll();

$labels = [
  'unpaid'=>'Menunggu Pembayaran',
  'processing'=>'Diproses',
  'done'=>'Selesai',
  'cancelled'=>'Dibatalkan',
  'returned'=>'Pengembalian',
];

$tabs_ui = [
  'unpaid'=>'Belum Dibayar',
  'processing'=>'Diproses',
  'done'=>'Selesai',
  'cancelled'=>'Dibatalkan',
  'returned'=>'Pengembalian',
];
?>
<div class="section-head">
  <h2>Pesanan Saya</h2>
  <div class="muted"><?= e($tabs_ui[$tab]) ?></div>
</div>

<div class="form-card" style="margin-top:12px;padding:12px">
  <div style="display:flex;flex-wrap:wrap;gap:8px">
    <?php foreach($tabs_ui as $k=>$v): ?>
      <a class="btn <?= $k===$tab ? 'primary' : '' ?>" href="/Digipren/pesanan.php?tab=<?= e($k) ?>" style="height:36px"><?= e($v) ?></a>
    <?php endforeach; ?>
  </div>
</div>

<div style="height:12px"></div>

<?php if(!$orders): ?>
  <div class="notice">Belum ada pesanan pada tab ini.</div>
<?php else: ?>
  <div class="form-card">
    <table class="table">
      <thead><tr><th>ID</th><th>Status</th><th>Total</th><th>Tanggal</th><th>Aksi</th></tr></thead>
      <tbody>
        <?php foreach($orders as $o): ?>
          <tr>
            <td>#<?= (int)$o['id'] ?></td>
            <td><?= e($labels[$o['status']] ?? $o['status']) ?></td>
            <td><?= e(money_idr($o['total_amount'])) ?></td>
            <td><?= e($o['created_at']) ?></td>
            <td>
              <a href="/Digipren/pesanan_detail.php?id=<?= (int)$o['id'] ?>">Detail</a>
              <?php if($o['status']==='unpaid'): ?>
                • <a href="/Digipren/pembayaran.php?id=<?= (int)$o['id'] ?>">Bayar</a>
                • <a href="/Digipren/order_action.php?action=cancel&id=<?= (int)$o['id'] ?>" onclick="return confirm('Batalkan pesanan?')">Batalkan</a>
              <?php elseif($o['status']==='processing'): ?>
                • <a href="/Digipren/order_action.php?action=receive&id=<?= (int)$o['id'] ?>" onclick="return confirm('Konfirmasi pesanan diterima?')">Diterima</a>
                • <a href="/Digipren/order_action.php?action=return&id=<?= (int)$o['id'] ?>" onclick="return confirm('Ajukan pengembalian?')">Return</a>
                • <a href="/Digipren/order_action.php?action=cancel&id=<?= (int)$o['id'] ?>" onclick="return confirm('Batalkan pesanan?')">Batalkan</a>
              <?php elseif($o['status']==='done'): ?>
                • <a href="/Digipren/order_action.php?action=return&id=<?= (int)$o['id'] ?>" onclick="return confirm('Ajukan pengembalian?')">Return</a>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>

<?php $active='orders'; require __DIR__ . '/includes/footer.php'; ?>
