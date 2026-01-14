<?php
$page_title = "Admin - Kategori";
require_once __DIR__ . '/../includes/header.php';
require_admin();
$rows = $pdo->query("SELECT id,name,slug FROM categories ORDER BY id DESC")->fetchAll();
?>
<div style="display:flex;justify-content:space-between;align-items:center;gap:10px">
  <h2 style="margin:0;font-size:18px">Kategori</h2>
  <a class="btn primary" href="/Digipren/admin/category_form.php">+ Tambah</a>
</div>
<div style="height:12px"></div>
<div class="form-card">
  <?php if(!$rows): ?>
    <div class="notice">Kategori masih kosong. Tambahkan minimal 1 kategori supaya bisa tambah produk.</div>
  <?php else: ?>
    <table class="table">
      <thead><tr><th>Nama</th><th>Slug</th><th>Aksi</th></tr></thead>
      <tbody>
        <?php foreach($rows as $r): ?>
          <tr>
            <td><?= e($r['name']) ?></td>
            <td><?= e($r['slug']) ?></td>
            <td>
              <a href="/Digipren/admin/category_form.php?id=<?= (int)$r['id'] ?>">Edit</a> |
              <a href="/Digipren/admin/category_delete.php?id=<?= (int)$r['id'] ?>" onclick="return confirm('Hapus kategori?')">Hapus</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
