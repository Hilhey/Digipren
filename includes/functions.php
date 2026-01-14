<?php
if (session_status()===PHP_SESSION_NONE) session_start();
function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function img_url(string $path): string {
  $path = str_replace('\\', '/', $path);
  $path = ltrim($path, '/');
  return "/Digipren/media.php?f=" . urlencode($path);
}
function slugify(string $s): string{
  $s=strtolower(trim($s));
  $s=preg_replace('/[^a-z0-9\s-]/','',$s);
  $s=preg_replace('/\s+/','-',$s);
  $s=preg_replace('/-+/','-',$s);
  return $s ?: 'kategori';
}
function flash_set(string $k,string $m){ $_SESSION['_flash'][$k]=$m; }
function flash_get(string $k){
  $m=$_SESSION['_flash'][$k]??null;
  unset($_SESSION['_flash'][$k]);
  return $m;
}
function current_user(): ?array{
  $uid=(int)($_SESSION['user_id']??0);
  if($uid<=0) return null;
  if(!isset($GLOBALS['pdo'])) return null;
  $pdo=$GLOBALS['pdo'];
  $st=$pdo->prepare("SELECT id,name,email,role FROM users WHERE id=? LIMIT 1");
  $st->execute([$uid]);
  $u=$st->fetch();
  return $u?:null;
}
function require_login(){
  if(!current_user()){ header("Location: /Digipren/auth/login.php"); exit; }
}
function require_admin(){
  $u=current_user();
  if(!$u || ($u['role']??'')!=='admin'){
    http_response_code(403);
    echo "<div style='padding:16px;font-family:system-ui'>Akses ditolak. Admin only.</div>";
    exit;
  }
}
function cart_count(): int{
  $u=current_user(); if(!$u) return 0;
  $pdo=$GLOBALS['pdo'];
  $st=$pdo->prepare("SELECT id FROM carts WHERE user_id=? LIMIT 1");
  $st->execute([(int)$u['id']]);
  $cart=$st->fetch();
  if(!$cart) return 0;
  $st=$pdo->prepare("SELECT COALESCE(SUM(qty),0) c FROM cart_items WHERE cart_id=?");
  $st->execute([(int)$cart['id']]);
  $row=$st->fetch();
  return (int)($row['c']??0);
}
function money_idr($n): string{ return "Rp".number_format((float)$n,0,',','.'); }
