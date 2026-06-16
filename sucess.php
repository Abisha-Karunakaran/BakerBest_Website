<?php
// success.php
if (session_status() === PHP_SESSION_NONE) session_start();

$DB_HOST='localhost'; $DB_USER='root'; $DB_PASS='root'; $DB_NAME='baker_best';
$conn = mysqli_connect($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME);
if (!$conn) die("DB connect error: ".mysqli_connect_error());

$order_id = intval($_GET['order'] ?? 0);
if ($order_id <= 0) {
    header("Location: index.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$order) {
    echo "Order not found";
    exit;
}

$stmt2 = $conn->prepare("SELECT item_name, price, qty FROM order_items WHERE order_id = ?");
$stmt2->bind_param("i", $order_id);
$stmt2->execute();
$items = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt2->close();
?>
<!doctype html>
<html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Order #<?php echo intval($order_id) ?> — BakerBest</title>
<style>
body{font-family:Poppins, sans-serif;padding:20px;background:#fff7ef;color:#744542}
.container{max-width:800px;margin:0 auto;background:#fff;padding:18px;border-radius:8px}
.table{width:100%;border-collapse:collapse}
.table td{padding:8px;border-bottom:1px solid #f0e6df}
.small{color:#6b584f}
</style>
</head><body>
<?php include 'header.php'; ?>
<div class="container">
  <h1>Order #<?php echo intval($order_id) ?></h1>
  <div class="small">Placed: <?php echo htmlspecialchars($order['created_at']) ?> • Status: <?php echo htmlspecialchars($order['status']) ?></div>

  <table class="table" style="margin-top:12px">
    <?php foreach($items as $it): ?>
      <tr>
        <td><?php echo htmlspecialchars($it['item_name']) ?></td>
        <td style="text-align:right"><?php echo intval($it['qty']) ?> x LKR <?php echo number_format($it['price'],2) ?></td>
        <td style="text-align:right">LKR <?php echo number_format($it['qty'] * $it['price'], 2) ?></td>
      </tr>
    <?php endforeach; ?>
    <tr><td colspan="3"><hr></td></tr>
    <tr><td></td><td style="text-align:right">Discount <?php echo number_format($order['discount_percent'],2) ?>%</td><td style="text-align:right"></td></tr>
    <tr><td></td><td style="text-align:right"><strong>Total</strong></td><td style="text-align:right"><strong>LKR <?php echo number_format($order['total'],2) ?></strong></td></tr>
  </table>

  <div style="margin-top:12px">
    <a href="print_bill.php?order=<?php echo intval($order_id) ?>" style="text-decoration:none;background:#744542;color:#fff;padding:10px 12px;border-radius:8px">Print / Save</a>
    <a href="menu.php" class="small" style="margin-left:12px">Back to menu</a>
  </div>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
