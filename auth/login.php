<?php
$page_title = "Login - Digipren";
require_once __DIR__ . '/../includes/header.php';

if (current_user()) { header("Location: /Digipren/index.php"); exit; }
$err = flash_get('err');
?>
<h2 style="margin:0 0 12px;font-size:18px">Masuk</h2>
<?php if ($err): ?><div class="error" style="margin-bottom:12px"><?= e($err) ?></div><?php endif; ?>

<div class="form-card">
  <form method="post" action="/Digipren/auth/login_handler.php">
    <div class="field"><label>Email</label><input name="email" type="email" required></div>
    <div class="field"><label>Password</label><input name="password" type="password" required></div>
    <button class="btn primary" style="width:100%" type="submit">Masuk</button>
  </form>
  <div style="margin-top:12px;color:var(--muted);font-weight:800">
    Belum punya akun? <a href="/Digipren/auth/register.php" style="color:var(--green)">Daftar</a>
  </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
