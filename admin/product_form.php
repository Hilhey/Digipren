<?php
$page_title = "Admin - Form Produk";
require_once __DIR__ . '/../includes/header.php';
require_admin();

$id = (int)($_GET['id'] ?? 0);
$editing = $id>0;

$cats = $pdo->query("SELECT id,name FROM categories ORDER BY name")->fetchAll();
$p = ['name'=>'','price'=>0,'stock'=>0,'description'=>'','category_id'=>0,'is_active'=>1];

if ($editing){
  $stmt=$pdo->prepare("SELECT * FROM products WHERE id=?");
  $stmt->execute([$id]);
  $p = $stmt->fetch();
  if(!$p){ echo "<div class='error'>Produk tidak ditemukan.</div>"; require __DIR__ . '/../includes/footer.php'; exit; }
}
?>
<h2 style="margin:0 0 12px;font-size:18px"><?= $editing ? 'Edit' : 'Tambah' ?> Produk</h2>

<?php if(!$cats): ?>
  <div class="notice" style="margin-bottom:12px">Kategori masih kosong. Tambahkan dulu di <a href="/Digipren/admin/categories.php" style="color:var(--green)">Kelola Kategori</a>.</div>
<?php endif; ?>

<div class="form-card">
  <form method="post" action="/Digipren/admin/product_save.php" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= (int)$id ?>">
    <div class="field"><label>Nama Produk</label><input name="name" value="<?= e($p['name']) ?>" required></div>

    <div class="field">
      <label>Kategori</label>
      <select name="category_id" required <?= !$cats ? 'disabled' : '' ?>>
        <?php if(!$cats): ?>
          <option>Tambah kategori dulu</option>
        <?php else: ?>
          <?php foreach($cats as $c): ?>
            <option value="<?= (int)$c['id'] ?>" <?= ((int)$p['category_id']===(int)$c['id'])?'selected':'' ?>>
              <?= e($c['name']) ?>
            </option>
          <?php endforeach; ?>
        <?php endif; ?>
      </select>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
      <div class="field"><label>Harga</label><input name="price" type="number" min="0" value="<?= (int)$p['price'] ?>" required></div>
      <div class="field"><label>Stok</label><input name="stock" type="number" min="0" value="<?= (int)$p['stock'] ?>" required></div>
    </div>

    <div class="field"><label>Deskripsi</label><textarea name="description" rows="4"><?= e($p['description']) ?></textarea></div>
    <div class="field"><label>Foto Produk (boleh lebih dari 1)</label><input type="file" name="images[]" accept="image/*" multiple></div>

    <div class="field">
      <label>Status</label>
      <select name="is_active">
        <option value="1" <?= (int)$p['is_active']===1?'selected':'' ?>>Aktif</option>
        <option value="0" <?= (int)$p['is_active']===0?'selected':'' ?>>Nonaktif</option>
      </select>
    </div>

    <button class="btn primary" style="width:100%" type="submit" <?= !$cats ? 'disabled' : '' ?>>Simpan</button>
  </form>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
