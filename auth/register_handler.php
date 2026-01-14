<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$pass = $_POST['password'] ?? '';

if ($name==='' || $email==='' || strlen($pass) < 6){
  flash_set('err','Data tidak valid.');
  header("Location: /Digipren/auth/register.php");
  exit;
}

$stmt = $pdo->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
$stmt->execute([$email]);
if ($stmt->fetch()){
  flash_set('err','Email sudah terdaftar.');
  header("Location: /Digipren/auth/register.php");
  exit;
}

$hash = password_hash($pass, PASSWORD_BCRYPT);
$stmt = $pdo->prepare("INSERT INTO users (name,email,password_hash,role) VALUES (?,?,?,'user')");
$stmt->execute([$name,$email,$hash]);

$_SESSION['user_id'] = (int)$pdo->lastInsertId();
header("Location: /Digipren/index.php");
