<?php
$page_title = "Admin - Pesanan";
require_once __DIR__ . '/../includes/header.php';
require_admin();

$rows = $pdo->query("SELECT o.id,o.status,o.total_amount,o.created_at,u.name,u.email
  FROM orders o JOIN users u ON u.id=o.user_id
  ORDER BY o.id DESC LIMIT 200")->fetchAll();

$labels = [
  'unpaid'=>'Belum Dibayar',
  'processing'=>'Diproses',
  'done'=>'Selesai',
  'cancelled'=>'Dibatalkan',
  'returned'=>'Pengembalian',
];
?>
<h2 style="margin:0 0 12px;font-size:18px">Pesanan</h2>
<div class="form-card">
  <table class="table">
    <thead><tr><th>ID</th><th>Pelanggan</th><th>Status</th><th>Total</th><th>Tanggal</th><th>Ubah</th></tr></thead>
    <tbody>
      <?php foreach($rows as $r): ?>
        <tr>
          <td>#<?= (int)$r['id'] ?></td>
          <td><?= e($r['name']) ?><div style="color:var(--muted);font-size:12px"><?= e($r['email']) ?></div></td>
          <td><?= e($labels[$r['status']] ?? $r['status']) ?></td>
          <td><?= e(money_idr($r['total_amount'])) ?></td>
          <td><?= e($r['created_at']) ?></td>
          <td>
            <form method="post" action="/Digipren/admin/order_status.php" style="display:flex;gap:8px;align-items:center">
              <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
              <select name="status">
                <?php foreach($labels as $k=>$v): ?>
                  <option value="<?= e($k) ?>" <?= $k===$r['status']?'selected':'' ?>><?= e($v) ?></option>
                <?php endforeach; ?>
              </select>
              <button class="btn" type="submit" style="height:36px">Save</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
