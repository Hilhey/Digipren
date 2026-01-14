<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$id = (int)($_POST['id'] ?? 0);
$name = trim($_POST['name'] ?? '');
$category_id = (int)($_POST['category_id'] ?? 0);
$price = (float)($_POST['price'] ?? 0);
$stock = (int)($_POST['stock'] ?? 0);
$desc = trim($_POST['description'] ?? '');
$is_active = (int)($_POST['is_active'] ?? 1);

if ($name==='' || $category_id<=0){ header("Location: /Digipren/admin/products.php"); exit; }

if ($id>0){
  $stmt=$pdo->prepare("UPDATE products SET name=?, category_id=?, price=?, stock=?, description=?, is_active=? WHERE id=?");
  $stmt->execute([$name,$category_id,$price,$stock,$desc,$is_active,$id]);
  $product_id=$id;
} else {
  $stmt=$pdo->prepare("INSERT INTO products (name, category_id, price, stock, description, is_active) VALUES (?,?,?,?,?,?)");
  $stmt->execute([$name,$category_id,$price,$stock,$desc,$is_active]);
  $product_id=(int)$pdo->lastInsertId();
}

$uploadDir = __DIR__ . '/../uploads/products';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

if (!empty($_FILES['images']['name'][0])) {
  for ($i=0; $i<count($_FILES['images']['name']); $i++){
    $tmp = $_FILES['images']['tmp_name'][$i];
    if (!is_uploaded_file($tmp)) continue;

    $ext = strtolower(pathinfo($_FILES['images']['name'][$i], PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg','jpeg','png','webp','gif'])) $ext='jpg';

    $fname = 'p'.$product_id.'_'.time().'_'.$i.'.'.$ext;
    $dest = $uploadDir . '/' . $fname;
    move_uploaded_file($tmp, $dest);

    $rel = 'uploads/products/' . $fname;
    $stmt=$pdo->prepare("INSERT INTO product_images (product_id, image_path) VALUES (?,?)");
    $stmt->execute([$product_id, $rel]);
  }
}

header("Location: /Digipren/admin/products.php");
