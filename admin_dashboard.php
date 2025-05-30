<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}
include 'db.php';

$orders = $conn->query("SELECT * FROM orders ORDER BY order_time DESC");
?>

<h2>All Orders</h2>
<table border="1">
<tr><th>Name</th><th>Phone</th><th>Address</th><th>Items</th><th>Time</th></tr>
<?php while ($row = $orders->fetch_assoc()) { ?>
<tr>
  <td><?= $row['customer_name'] ?></td>
  <td><?= $row['phone'] ?></td>
  <td><?= $row['address'] ?></td>
  <td><?= $row['items'] ?></td>
  <td><?= $row['order_time'] ?></td>
</tr>
<?php } ?>
</table>
