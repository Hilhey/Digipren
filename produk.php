<?php
require_once __DIR__ . '/includes/header.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT p.*, c.name as category FROM products p LEFT JOIN categories c ON c.id=p.category_id WHERE p.id=? LIMIT 1");
$stmt->execute([$id]);
$p = $stmt->fetch();
if (!$p) { echo "<div class='error'>Produk tidak ditemukan.</div>"; require __DIR__ . '/includes/footer.php'; exit; }

$imgsSt = $pdo->prepare("SELECT image_path FROM product_images WHERE product_id=? ORDER BY id ASC");
$imgsSt->execute([$id]);
$imgs = $imgsSt->fetchAll();
$me = current_user();
$is_admin = $me && ($me['role'] ?? '')==='admin';
?>
<div class="form-card">
  <div style="display:grid;grid-template-columns:1fr;gap:14px">
    <div class="thumb" style="border-radius:16px;overflow:hidden">
      <?php if ($imgs): ?>
        <img src="<?= e(img_url($imgs[0]['image_path'])) ?>" alt="">
      <?php else: ?>
        <div class="ph" style="padding:30px">No Image</div>
      <?php endif; ?>
    </div>

    <div>
      <div style="display:flex;flex-wrap:wrap;gap:8px;align-items:center;justify-content:space-between">
        <div>
          <div style="font-weight:1000;font-size:18px"><?= e($p['name']) ?></div>
          <div style="color:var(--muted);margin-top:4px"><?= e($p['category'] ?? '-') ?> • Terjual 120+</div>
        </div>
        <div style="font-weight:1000;font-size:18px"><?= e(money_idr($p['price'])) ?></div>
      </div>

      <div style="margin-top:10px;color:var(--muted);font-weight:900">Stok: <?= (int)$p['stock'] ?></div>
      <p style="margin-top:10px;line-height:1.7;color:#374151"><?= nl2br(e($p['description'])) ?></p>

      <?php if(!$is_admin): ?>
      <form method="post" action="/Digipren/keranjang_handler.php" style="margin-top:12px;display:flex;gap:10px;align-items:center;flex-wrap:wrap">
        <input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>">
        <input type="number" name="qty" value="1" min="1" max="<?= (int)$p['stock'] ?>" style="width:110px">
        <button class="btn primary" type="submit">Tambah ke Keranjang</button>
        <a class="btn" href="/Digipren/keranjang.php">Lihat Keranjang</a>
      </form>
      <?php else: ?>
        <div class="notice" style="margin-top:12px">Mode admin: pembelian disembunyikan. Kelola produk dari dashboard.</div>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
