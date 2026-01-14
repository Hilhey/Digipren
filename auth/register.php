<?php
$page_title = "Daftar - Digipren";
require_once __DIR__ . '/../includes/header.php';

if (current_user()) { header("Location: /Digipren/index.php"); exit; }
$err = flash_get('err');
?>
<h2 style="margin:0 0 12px;font-size:18px">Daftar</h2>
<?php if ($err): ?><div class="error" style="margin-bottom:12px"><?= e($err) ?></div><?php endif; ?>

<div class="form-card">
  <form method="post" action="/Digipren/auth/register_handler.php">
    <div class="field"><label>Nama</label><input name="name" required></div>
    <div class="field"><label>Email</label><input name="email" type="email" required></div>
    <div class="field"><label>Password</label><input name="password" type="password" required minlength="6"></div>
    <button class="btn primary" style="width:100%" type="submit">Buat Akun</button>
  </form>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
