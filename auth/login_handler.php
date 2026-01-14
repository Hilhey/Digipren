<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

$email = trim($_POST['email'] ?? '');
$pass = $_POST['password'] ?? '';

$stmt = $pdo->prepare("SELECT id, password_hash FROM users WHERE email=? LIMIT 1");
$stmt->execute([$email]);
$u = $stmt->fetch();

if (!$u || !password_verify($pass, $u['password_hash'] ?? '')) {
  flash_set('err','Email atau password salah.');
  header("Location: /Digipren/auth/login.php");
  exit;
}

$_SESSION['user_id'] = (int)$u['id'];
header("Location: /Digipren/index.php");
