<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

$page_title = $page_title ?? 'Digipren';
$q = trim($_GET['q'] ?? '');
$me = current_user();
$is_admin = $me && ($me['role'] ?? '') === 'admin';
$cart_count = $is_admin ? 0 : cart_count();

$cats = $pdo->query("SELECT name, slug FROM categories ORDER BY name")->fetchAll();
$active_cat = trim($_GET['cat'] ?? '');
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= e($page_title) ?></title>
  <link rel="stylesheet" href="/Digipren/assets/css/app.css?v=6">
</head>
<body>
<div class="strip">
  <div class="container">
    <div>Gratis Ongkir • Promo Harian • Belanja aman di <b>Digipren</b></div>
    <div style="opacity:.9">Support: WhatsApp / Email</div>
  </div>
</div>

<header class="header">
  <div class="container">
    <div class="header-inner">
      <a class="brand" href="/Digipren/index.php">
        <img class="brand-logo" src="/Digipren/assets/img/digipren-logo.png" alt="Digipren">
        <span class="brand-name">Digipren</span>
      </a>

      <form class="search" method="get" action="/Digipren/index.php">
        <div class="box">
          <span>🔎</span>
          <input name="q" value="<?= e($q) ?>" placeholder="Cari produk, kategori, promo...">
        </div>
        <button type="submit">Cari</button>
      </form>

      <?php if (!$is_admin): ?>
        <a class="icon-btn" href="/Digipren/keranjang.php" aria-label="Keranjang">🛒
          <?php if ($cart_count > 0): ?><span class="badge" style="position:absolute;top:6px;right:6px"><?= (int)$cart_count ?></span><?php endif; ?>
        </a>
      <?php endif; ?>

      <div class="actions">
        <?php if (!$me): ?>
          <a class="btn" href="/Digipren/auth/login.php">Masuk</a>
          <a class="btn primary" href="/Digipren/auth/register.php">Daftar</a>
        <?php else: ?>
          <?php if ($is_admin): ?><span class="btn ghost" style="border:1px dashed rgba(0,0,0,.2)">Mode Admin</span><?php endif; ?>
          <?php if ($is_admin): ?><a class="btn primary" href="/Digipren/admin/index.php">Dashboard</a><?php else: ?><a class="btn" href="/Digipren/akun.php">Akun</a><?php endif; ?>
          <a class="btn" href="/Digipren/auth/logout.php">Keluar</a>
        <?php endif; ?>
      </div>
    </div>

    <?php if ($cats): ?>
      <div class="catsbar">
        <div class="row">
          <a class="chip <?= $active_cat===''?'active':'' ?>" href="/Digipren/index.php">Semua</a>
          <?php foreach ($cats as $c): ?>
            <a class="chip <?= ($active_cat===($c['slug']??''))?'active':'' ?>" href="/Digipren/index.php?cat=<?= e($c['slug']) ?>"><?= e($c['name']) ?></a>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>
  </div>
</header>

<main class="container main">
