<?php
$page_title="Akun";
require_once __DIR__ . '/includes/header.php';
require_login();
$u = current_user();
$is_admin = ($u['role'] ?? '') === 'admin';
?>
<div class="form-card">
  <div style="display:flex;gap:12px;align-items:center">
    <div style="width:56px;height:56px;border-radius:18px;background:#e5e7eb;display:flex;align-items:center;justify-content:center;font-size:22px;font-weight:1000">
      <?= strtoupper(substr($u['name'] ?? 'U',0,1)) ?>
    </div>
    <div>
      <div style="font-weight:1000;font-size:16px"><?= e($u['name']) ?></div>
      <div style="color:var(--muted);font-size:13px"><?= e($u['email']) ?></div>
      <?php if($is_admin): ?><div class="small" style="margin-top:4px">Role: <b>Admin</b></div><?php endif; ?>
    </div>
  </div>
</div>

<div style="height:12px"></div>

<div class="form-card">
  <?php if($is_admin): ?>
    <a class="btn primary" href="/Digipren/admin/index.php" style="width:100%;margin-bottom:10px">🛠️ Dashboard Admin</a>
  <?php else: ?>
    <a class="btn" href="/Digipren/pesanan.php" style="width:100%;margin-bottom:10px">🧾 Pesanan Saya</a>
    <a class="btn" href="/Digipren/keranjang.php" style="width:100%;margin-bottom:10px">🛒 Keranjang</a>
  <?php endif; ?>
  <a class="btn primary" href="/Digipren/auth/logout.php" style="width:100%">Keluar</a>
</div>

<?php $active='me'; require __DIR__ . '/includes/footer.php'; ?>
