<?php
$page_title = "Pembayaran - QRIS";
require_once __DIR__ . '/includes/header.php';
require_login();

$u = current_user();
if (($u['role'] ?? '') === 'admin') { echo "<div class='notice'>Akun admin tidak punya pembayaran.</div>"; require __DIR__ . '/includes/footer.php'; exit; }

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT id, status, total_amount, created_at FROM orders WHERE id=? AND user_id=? LIMIT 1");
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

$code = 'DP' . str_pad((string)$o['id'], 8, '0', STR_PAD_LEFT);
?>
<div class="section-head">
  <h2>Pembayaran QRIS</h2>
  <div class="muted">Simulasi pembayaran</div>
</div>

<div class="qr-wrap" style="margin-top:12px">
  <div class="qr-card">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:10px">
      <div style="font-weight:1000">Total</div>
      <div style="font-weight:1000;font-size:18px"><?= e(money_idr($o['total_amount'])) ?></div>
    </div>
    <div class="small" style="margin-top:6px">Kode pembayaran: <b><?= e($code) ?></b></div>

    <div style="margin-top:14px" class="qr-box">
      <div class="tag">QRIS • Dummy</div>
      <div class="center">
        <div style="font-size:14px">Scan untuk bayar</div>
        <div style="font-size:12px;color:var(--muted);margin-top:4px"><?= e($code) ?></div>
      </div>
    </div>

    <?php if($o['status']==='unpaid'): ?>
      <form method="post" action="/Digipren/pembayaran_confirm.php" style="margin-top:14px;display:flex;gap:10px;flex-wrap:wrap">
        <input type="hidden" name="id" value="<?= (int)$o['id'] ?>">
        <button class="btn primary" type="submit">Simulasikan Scan QR (Bayar)</button>
        <a class="btn" href="/Digipren/order_action.php?action=cancel&id=<?= (int)$o['id'] ?>" onclick="return confirm('Batalkan pesanan?')">Batalkan</a>
      </form>
      <div class="small" style="margin-top:10px">Setelah tombol “Bayar” ditekan, status akan berubah menjadi <b>Diproses</b>.</div>
    <?php else: ?>
      <div class="notice" style="margin-top:14px">Status saat ini: <?= e($labels[$o['status']] ?? $o['status']) ?></div>
      <div style="margin-top:12px">
        <a class="btn primary" href="/Digipren/pesanan_detail.php?id=<?= (int)$o['id'] ?>">Lihat Detail Pesanan</a>
      </div>
    <?php endif; ?>
  </div>

  <div class="qr-card">
    <div style="font-weight:1000">Panduan</div>
    <ol style="margin:10px 0 0;padding-left:18px;color:#374151;line-height:1.7">
      <li>Buka aplikasi e-wallet / mobile banking</li>
      <li>Pilih menu <b>QRIS</b> lalu arahkan ke QR (simulasi)</li>
      <li>Untuk demo, tekan tombol <b>Simulasikan Scan QR</b></li>
      <li>Pesanan akan masuk ke tab <b>Diproses</b></li>
    </ol>
    <div class="small" style="margin-top:10px">Catatan: Ini masih dummy untuk kebutuhan simulasi sistem.</div>
  </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
