<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$id=(int)($_POST['id'] ?? 0);
$status=$_POST['status'] ?? 'unpaid';
$allowed=['unpaid','processing','done','cancelled','returned'];
if($id>0 && in_array($status,$allowed,true)){
  $pdo->prepare("UPDATE orders SET status=? WHERE id=?")->execute([$status,$id]);
}
header("Location: /Digipren/admin/orders.php");
