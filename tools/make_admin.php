<?php
require_once __DIR__ . '/../config/db.php';

$email = 'admin@digipren.test';
$pass  = 'admin12345';
$name  = 'Admin Digipren';

$stmt = $pdo->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
$stmt->execute([$email]);
if ($stmt->fetch()){
  echo "Admin already exists: {$email}";
  exit;
}

$hash = password_hash($pass, PASSWORD_BCRYPT);
$stmt = $pdo->prepare("INSERT INTO users (name,email,password_hash,role) VALUES (?,?,?,'admin')");
$stmt->execute([$name,$email,$hash]);

echo "ADMIN CREATED: {$email} / {$pass}";
