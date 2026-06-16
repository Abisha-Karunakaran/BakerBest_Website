<?php
include_once "admin_header.php";

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

// Database connection
$conn = mysqli_connect("localhost", "root", "root", "baker_best");

// Handle update or delete
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_status'])) {
        $order_id = $_POST['order_id'];
        $new_status = $_POST['status'];
        $reason = trim($_POST['reason'] ?? '');

        $stmt = $conn->prepare("UPDATE orders SET status=?, reason=? WHERE id=?");
        $stmt->bind_param("ssi", $new_status, $reason, $order_id);
        $stmt->execute();
        $stmt->close();

        header("Location: order_management.php?updated=1");
        exit();
    }

    if (isset($_POST['delete_order'])) {
        $order_id = $_POST['order_id'];
        $stmt = $conn->prepare("DELETE FROM orders WHERE id=?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $stmt->close();

        header("Location: order_management.php?deleted=1");
        exit();
    }
}

// Fetch all orders
$orders = mysqli_query($conn, "SELECT * FROM orders ORDER BY created_at DESC");
?>

<style>
.table-wrapper {
    max-width: 1300px;
    background: #fff;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0 3px 12px rgba(0,0,0,0.08);
    font-size: 13px;
    margin: 20px auto;
    margin-left: 40px;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}
th, td {
    padding: 8px;
    border-bottom: 1px solid #ddd;
    text-align: center;
}
th {
    background: #744542;
    color: #fff;
    font-size: 13px;
}
.order-items {
    background: #f3e8e6;
    padding: 6px;
    border-radius: 6px;
    max-width: 300px;
    overflow-x: auto;
    font-size: 12px;
    text-align: left;
}
.update-btn, .delete-btn {
    padding: 4px 8px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 12px;
    margin-left: 5px;
}
.update-btn { background: #28a745; color: white; }
.update-btn:hover { background: #218838; }
.delete-btn { background: #dc3545; color: white; }
.delete-btn:hover { background: #c82333; }
.reason-input { padding:3px; font-size:12px; width: 120px; }
</style>

<div class="main-content">
    <h2 style="color:#744542; text-align:center; margin-top:50px;">Manage Orders</h2>

    <?php if (isset($_GET['updated'])) { ?>
        <div style="padding:8px;background:#d4edda;color:#155724;margin-bottom:10px;margin-left: 40px;border-radius:6px;font-size:13px;">
            Status Updated Successfully!
        </div>
    <?php } ?>
    <?php if (isset($_GET['deleted'])) { ?>
        <div style="padding:8px;background:#f8d7da;color:#721c24;margin-bottom:10px;border-radius:6px;font-size:13px;">
            Order Deleted Successfully!
        </div>
    <?php } ?>

    <div class="table-wrapper">
        <table>
            <tr>
                <th>User Email</th>
                <th>Order Date</th>
                <th>Pickup Date</th>
                <th>Pickup Time</th>
                <th>Payment Method</th>
                <th>Action</th> <!-- dropdown + update + reason + delete -->
                <th>Status</th> <!-- shows current status -->
                <th>Order Details</th>
            </tr>

            <?php while ($row = mysqli_fetch_assoc($orders)) {
                $items = json_decode($row['order_data'], true) ?? [];
            ?>
            <tr>
                <td><?= htmlspecialchars($row['customer_email']); ?></td>
                <td><?= $row['created_at']; ?></td>
                <td><?= $row['pickup_date']; ?></td>
                <td><?= $row['pickup_time']; ?></td>
                <td><?= htmlspecialchars($row['payment_method']); ?></td>

                <!-- Action column -->
                <td>
                    <form method="POST" style="display:flex;justify-content:center;align-items:center;gap:3px;" onsubmit="return validateReason(this);">
                        <input type="hidden" name="order_id" value="<?= $row['id']; ?>">
                        <select name="status" class="status-select" onchange="checkReason(this);">
                            <?php
                            $statuses = ["Pending", "Processing", "Ready for Pickup", "Completed", "Cancelled"];
                            foreach ($statuses as $st) {
                                $sel = ($row["status"] == $st) ? "selected" : "";
                                echo "<option $sel>$st</option>";
                            }
                            ?>
                        </select>
                        <input type="text" name="reason" class="reason-input" placeholder="Reason" value="<?= htmlspecialchars($row['reason'] ?? ''); ?>">
                        <button type="submit" name="update_status" class="update-btn">Update</button>
                        <button type="submit" name="delete_order" class="delete-btn" onclick="return confirm('Are you sure you want to delete this order?');">Delete</button>
                    </form>
                </td>

                <!-- Status column -->
                <td><?= htmlspecialchars($row['status']); ?></td>

                <!-- Order Details -->
                <td>
                    <div class="order-items">
                        <?php if (count($items) === 0) { ?>
                            <i>No items found</i>
                        <?php } else {
                            foreach ($items as $item) { ?>
                                <b><?= htmlspecialchars($item['name'] ?? 'Unknown'); ?></b> —
                                <?= intval($item['qty']); ?> × LKR <?= number_format(floatval($item['price']),2); ?><br>
                            <?php }
                        } ?>
                    </div>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</div>

<script>
// Validate reason for cancellation
function validateReason(form) {
    const status = form.querySelector('.status-select').value;
    const reason = form.querySelector('.reason-input').value.trim();

    if(status === 'Cancelled' && reason === '') {
        alert('Please provide a reason for cancelling the order.');
        return false;
    }
    return true;
}

// Highlight reason input when status is Cancelled
function checkReason(select) {
    const reasonInput = select.parentElement.querySelector('.reason-input');
    if(select.value === 'Cancelled') {
        reasonInput.style.border = '2px solid red';
    } else {
        reasonInput.style.border = '1px solid #ccc';
    }
}
</script>
