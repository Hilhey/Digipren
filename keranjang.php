<?php
require_once __DIR__ . '/includes/header.php';
require_login();

$u = current_user();
if (($u['role'] ?? '') === 'admin') { echo "<div class='notice'>Akun admin tidak memiliki keranjang.</div>"; require __DIR__ . '/includes/footer.php'; exit; }

$stmt = $pdo->prepare("SELECT id FROM carts WHERE user_id=? LIMIT 1");
$stmt->execute([(int)$u['id']]);
$cart = $stmt->fetch();

$items = [];
$total = 0;
if ($cart) {
  $stmt = $pdo->prepare("SELECT ci.id,ci.qty,p.name,p.price,
    (SELECT image_path FROM product_images WHERE product_id=p.id ORDER BY id ASC LIMIT 1) as img
    FROM cart_items ci JOIN products p ON p.id=ci.product_id
    WHERE ci.cart_id=? ORDER BY ci.id DESC");
  $stmt->execute([(int)$cart['id']]);
  $items = $stmt->fetchAll();
  foreach ($items as $it) $total += $it['price'] * $it['qty'];
}
?>
<div class="section-head">
  <h2>Keranjang</h2>
  <div class="muted"><?= $items ? count($items)." item" : "" ?></div>
</div>

<?php if (!$items): ?>
  <div class="notice" style="margin-top:12px">Keranjang masih kosong.</div>
<?php else: ?>
  <div class="form-card" style="margin-top:12px">
    <?php foreach ($items as $it): ?>
      <div style="display:flex;gap:12px;padding:10px 0;border-bottom:1px solid var(--line)">
        <div style="width:74px;height:74px;border-radius:16px;overflow:hidden;background:#f3f4f6">
          <?php if ($it['img']): ?><img src="<?= e(img_url($it['img'])) ?>" style="width:100%;height:100%;object-fit:cover"><?php endif; ?>
        </div>
        <div style="flex:1">
          <div style="font-weight:1000"><?= e($it['name']) ?></div>
          <div style="color:var(--muted);font-size:12px;margin-top:2px"><?= e(money_idr($it['price'])) ?></div>
          <form method="post" action="/Digipren/keranjang_update.php" style="margin-top:8px;display:flex;gap:8px;align-items:center;flex-wrap:wrap">
            <input type="hidden" name="item_id" value="<?= (int)$it['id'] ?>">
            <input type="number" name="qty" value="<?= (int)$it['qty'] ?>" min="1" style="width:96px">
            <button class="btn" type="submit">Update</button>
            <a class="btn" href="/Digipren/keranjang_remove.php?id=<?= (int)$it['id'] ?>" onclick="return confirm('Hapus item?')">Hapus</a>
          </form>
        </div>
        <div style="font-weight:1000"><?= e(money_idr($it['price']*$it['qty'])) ?></div>
      </div>
    <?php endforeach; ?>

    <div style="display:flex;justify-content:space-between;align-items:center;margin-top:12px">
      <div style="font-weight:1000">Total</div>
      <div style="font-weight:1000;font-size:18px"><?= e(money_idr($total)) ?></div>
    </div>

    <a class="btn primary" href="/Digipren/checkout.php" style="margin-top:12px;width:100%">Lanjut Checkout</a>
  </div>
<?php endif; ?>

<?php $active='cart'; require __DIR__ . '/includes/footer.php'; ?>
