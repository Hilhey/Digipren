<?php
$page_title="Digipren - Belanja Online";
require_once __DIR__ . '/includes/header.php';

$cat=trim($_GET['cat']??'');
$q=trim($_GET['q']??'');

$params=[];
$where="p.is_active=1";
if($cat!==''){ $where.=" AND c.slug=?"; $params[]=$cat; }
if($q!==''){ $where.=" AND (p.name LIKE ? OR p.description LIKE ?)"; $params[]="%$q%"; $params[]="%$q%"; }

$sql="SELECT p.id,p.name,p.price,p.stock,
      (SELECT image_path FROM product_images WHERE product_id=p.id ORDER BY id ASC LIMIT 1) img
      FROM products p
      LEFT JOIN categories c ON c.id=p.category_id
      WHERE $where
      ORDER BY p.id DESC
      LIMIT 60";
$st=$pdo->prepare($sql);
$st->execute($params);
$products=$st->fetchAll();

$cats=$pdo->query("SELECT name,slug FROM categories ORDER BY name")->fetchAll();
$me = current_user();
$is_admin = $me && ($me['role'] ?? '')==='admin';
?>

<div class="hero">
  <div class="left">
    <h1>Belanja mudah, cepat, dan aman di Digipren</h1>
    <p>Temukan produk pilihan dengan harga terbaik. Tampilan nyaman di HP, tablet, dan laptop.</p>

    <div class="cta">
      <?php if($is_admin): ?>
        <a class="btn primary" href="/Digipren/admin/products.php">Kelola Produk</a>
        <a class="btn" href="/Digipren/admin/categories.php">Kelola Kategori</a>
      <?php else: ?>
        <a class="btn primary" href="#produk">Mulai Belanja</a>
        <?php if($me): ?><a class="btn" href="/Digipren/pesanan.php">Cek Pesanan</a><?php endif; ?>
      <?php endif; ?>
    </div>
  </div>

  <div class="note">
    ✨ Promo harian • Gratis ongkir • Garansi aman
  </div>
</div>

<?php if($cats): ?>
<div class="cat-grid">
  <?php foreach($cats as $c): ?>
    <a class="cat-item" href="/Digipren/index.php?cat=<?= e($c['slug']) ?>">
      <div class="cat-ico">🏷️</div>
      <div class="cat-name"><?= e($c['name']) ?></div>
    </a>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<div class="section-head" id="produk">
  <h2>Produk untuk kamu</h2>
  <div class="muted"><?= $q ? "Hasil pencarian: ".e($q) : "Rekomendasi terbaru" ?></div>
</div>

<?php if(!$products): ?>
  <div class="notice" style="margin-top:12px">
    Produk belum ada.
    <?php if($is_admin): ?>Silakan tambah produk dari menu Admin.<?php endif; ?>
  </div>
<?php else: ?>
  <div class="grid">
    <?php foreach($products as $p): ?>
      <a class="card" href="/Digipren/produk.php?id=<?= (int)$p['id'] ?>">
        <div class="thumb">
          <?php if($p['img']): ?>
            <img src="<?= e(img_url($p['img'])) ?>" alt="">
          <?php else: ?>
            <div class="ph">No Image</div>
          <?php endif; ?>
        </div>
        <div class="info">
          <div class="name"><?= e($p['name']) ?></div>
          <div class="price"><?= e(money_idr($p['price'])) ?></div>
          <div class="meta">
            <span>Stok <?= (int)$p['stock'] ?></span>
            <span style="color:var(--brand);font-weight:1000">⭐ 4.8</span>
          </div>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php $active='home'; require __DIR__ . '/includes/footer.php'; ?>
