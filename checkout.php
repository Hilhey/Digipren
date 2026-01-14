<?php
require_once __DIR__ . '/includes/header.php';
require_login();
$u = current_user();
if (($u['role'] ?? '') === 'admin') { echo "<div class='notice'>Akun admin tidak bisa checkout.</div>"; require __DIR__ . '/includes/footer.php'; exit; }

$stmt = $pdo->prepare("SELECT id FROM carts WHERE user_id=? LIMIT 1");
$stmt->execute([(int)$u['id']]);
$cart = $stmt->fetch();
if (!$cart) { echo "<div class='notice'>Keranjang kosong.</div>"; require __DIR__ . '/includes/footer.php'; exit; }

$stmt = $pdo->prepare("SELECT ci.product_id, ci.qty, p.price
  FROM cart_items ci JOIN products p ON p.id=ci.product_id
  WHERE ci.cart_id=?");
$stmt->execute([(int)$cart['id']]);
$items = $stmt->fetchAll();
if (!$items) { echo "<div class='notice'>Keranjang kosong.</div>"; require __DIR__ . '/includes/footer.php'; exit; }

$total = 0;
foreach($items as $it){ $total += $it['price']*$it['qty']; }
?>
<div class="section-head">
  <h2>Checkout</h2>
  <div class="muted">Pilih metode pembayaran</div>
</div>

<div class="form-card" style="margin-top:12px">
  <div style="display:flex;justify-content:space-between;align-items:center">
    <div style="font-weight:1000">Total Pembayaran</div>
    <div style="font-weight:1000;font-size:18px"><?= e(money_idr($total)) ?></div>
  </div>

  <form method="post" action="/Digipren/checkout_place.php" style="margin-top:12px">
    <div class="field">
      <label>Metode Pembayaran</label>
      <select name="payment_method" required>
        <option value="qris">QRIS (Simulasi)</option>
      </select>
      <div class="small">Saat ini tersedia QRIS simulasi. Nanti bisa upgrade ke QRIS asli.</div>
    </div>

    <div class="field">
      <label>Catatan (opsional)</label>
      <textarea name="note" rows="3" placeholder="Misal: kirim sore"></textarea>
    </div>

    <button class="btn primary" style="width:100%" type="submit">Buat Pesanan & Bayar</button>
  </form>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
