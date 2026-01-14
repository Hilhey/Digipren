<?php
require_once __DIR__ . '/functions.php';
$me = current_user();
$is_admin = $me && ($me['role'] ?? '') === 'admin';
if ($is_admin) return; // admin tidak butuh bottom nav

$active = $active ?? 'home';
function navItem($key, $href, $label, $icon, $activeKey){
  $cls = $key===$activeKey ? 'active' : '';
  return "<a class='$cls' href='$href'><span class='ni'>$icon</span><span class='nl'>$label</span></a>";
}
?>
<nav class="bottom-nav">
  <?= navItem('home','/Digipren/index.php','Beranda','🏠',$active) ?>
  <?= navItem('cart','/Digipren/keranjang.php','Keranjang','🛒',$active) ?>
  <?= navItem('orders','/Digipren/pesanan.php','Pesanan','🧾',$active) ?>
  <?= navItem('me','/Digipren/akun.php','Saya','👤',$active) ?>
</nav>
