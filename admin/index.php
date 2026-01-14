<?php
$page_title = "Admin - Digipren";
require_once __DIR__ . '/../includes/header.php';
require_admin();
?>
<div class="section-head">
  <h2>Dashboard Admin</h2>
  <div class="muted">Kelola toko • produk • kategori • pesanan</div>
</div>

<div class="form-card" style="margin-top:12px">
  <div style="display:grid;gap:10px">
    <a class="btn" href="/Digipren/admin/categories.php">🏷️ Kelola Kategori</a>
    <a class="btn" href="/Digipren/admin/products.php">📦 Kelola Produk</a>
    <a class="btn" href="/Digipren/admin/orders.php">🧾 Kelola Pesanan Pelanggan</a>
  </div>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
