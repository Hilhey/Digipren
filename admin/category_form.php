<?php
$page_title = "Admin - Form Kategori";
require_once __DIR__ . '/../includes/header.php';
require_admin();

$id = (int)($_GET['id'] ?? 0);
$editing = $id>0;
$cat = ['name'=>'','slug'=>''];

if ($editing){
  $stmt = $pdo->prepare("SELECT id,name,slug FROM categories WHERE id=?");
  $stmt->execute([$id]);
  $cat = $stmt->fetch();
  if(!$cat){ echo "<div class='error'>Kategori tidak ditemukan.</div>"; require __DIR__ . '/../includes/footer.php'; exit; }
}
?>
<h2 style="margin:0 0 12px;font-size:18px"><?= $editing ? 'Edit' : 'Tambah' ?> Kategori</h2>
<div class="form-card">
  <form method="post" action="/Digipren/admin/category_save.php">
    <input type="hidden" name="id" value="<?= (int)$id ?>">
    <div class="field"><label>Nama</label><input name="name" value="<?= e($cat['name']) ?>" required></div>
    <div class="field"><label>Slug (opsional)</label><input name="slug" value="<?= e($cat['slug']) ?>" placeholder="makanan"></div>
    <button class="btn primary" style="width:100%" type="submit">Simpan</button>
  </form>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
