<?php
// Railway MySQL variables: MYSQLHOST, MYSQLPORT, MYSQLDATABASE, MYSQLUSER, MYSQLPASSWORD
// Fallbacks below keep it working on localhost.
$DB_HOST = getenv('MYSQLHOST') ?: '127.0.0.1';
$DB_PORT = getenv('MYSQLPORT') ?: 3306;
$DB_NAME = getenv('MYSQLDATABASE') ?: 'bd_muhammadhilman';
$DB_USER = getenv('MYSQLUSER') ?: 'root';
$DB_PASS = getenv('MYSQLPASSWORD') ?: '';

try{
  $pdo = new PDO(
    "mysql:host={$DB_HOST};port={$DB_PORT};dbname={$DB_NAME};charset=utf8mb4",
    $DB_USER,
    $DB_PASS,
    [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
  );
}catch(Throwable $e){
  http_response_code(500);
  echo "DB connection failed: ".htmlspecialchars($e->getMessage());
  exit;
}
