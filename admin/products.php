<?php
$page_title = "Admin - Produk";
require_once __DIR__ . '/../includes/header.php';
require_admin();

$rows = $pdo->query("SELECT p.id,p.name,p.price,p.stock,p.is_active,c.name as cat
  FROM products p LEFT JOIN categories c ON c.id=p.category_id
  ORDER BY p.id DESC")->fetchAll();
?>
<div style="display:flex;justify-content:space-between;align-items:center;gap:10px">
  <h2 style="margin:0;font-size:18px">Produk</h2>
  <a class="btn primary" href="/Digipren/admin/product_form.php">+ Tambah</a>
</div>
<div style="height:12px"></div>
<div class="form-card">
  <table class="table">
    <thead><tr><th>Nama</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Status</th><th>Aksi</th></tr></thead>
    <tbody>
      <?php foreach($rows as $r): ?>
        <tr>
          <td><?= e($r['name']) ?></td>
          <td><?= e($r['cat'] ?? '-') ?></td>
          <td><?= e(money_idr($r['price'])) ?></td>
          <td><?= (int)$r['stock'] ?></td>
          <td><?= (int)$r['is_active'] ? 'Aktif' : 'Nonaktif' ?></td>
          <td><a href="/Digipren/admin/product_form.php?id=<?= (int)$r['id'] ?>">Edit</a></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
