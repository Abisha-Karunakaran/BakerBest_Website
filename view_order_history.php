<?php
session_start();
include 'Backend/db.php';

// Get current user info
$user_name = $_SESSION['user_name'] ?? 'Guest';
$user_email = $_SESSION['user_email'] ?? 'guest@example.com';

// Fetch all past orders for this user
$stmt = $conn->prepare("SELECT * FROM orders WHERE customer_email = ? ORDER BY created_at DESC");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="order-history-container">
    <h2>Your Past Orders</h2>

    <?php if ($result->num_rows === 0) { ?>
        <p>No past orders found.</p>
    <?php } else { ?>
        <div class="table-wrapper">
        <table id="orderHistoryTable">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Items</th>
                    <th>Total Price</th>
                    <th>Pickup Date</th>
                    <th>Pickup Time</th>
                    <th>Payment Method</th>
                    <th>Status</th>
                    <th>Reason</th>
                    <th>Order Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { 
                    $items = json_decode($row['order_data'], true);
                    $status = $row['status'] ?? 'Pending';
                    $reason = $row['reason'] ?? '';
                ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td>
                        <?php
                        if (is_array($items) && count($items) > 0) {
                            foreach ($items as $item) {
                                echo "<b>".htmlspecialchars($item['name'])."</b> — ".
                                     intval($item['qty'])." × LKR ".number_format(floatval($item['price']), 2)."<br>";
                            }
                        } else {
                            echo "<i>No items found</i>";
                        }
                        ?>
                    </td>
                    <td>LKR <?= number_format($row['total_price'], 2) ?></td>
                    <td><?= $row['pickup_date'] ?></td>
                    <td><?= $row['pickup_time'] ?></td>
                    <td><?= htmlspecialchars($row['payment_method']) ?></td>
                    <td>
                        <?php
                        $color = match($status) {
                            'Pending' => '#ffc107',
                            'Processing' => '#17a2b8',
                            'Ready for Pickup' => '#007bff',
                            'Completed' => '#28a745',
                            'Cancelled' => '#dc3545',
                            default => '#6c757d'
                        };
                        echo "<span class='status-badge' style='background:$color;'>$status</span>";
                        ?>
                    </td>
                    <td><?= ($status === 'Cancelled' && !empty($reason)) ? htmlspecialchars($reason) : '-' ?></td>
                    <td><?= $row['created_at'] ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        </div>
    <?php } ?>
</div>

<?php
$stmt->close();
$conn->close();
?>

<!-- ====== CSS ====== -->
<style>
.order-history-container {
    max-width: 1100px;
    margin: 20px auto;
    padding: 15px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    font-size: 14px;
}

.order-history-container h2 {
    text-align: center;
    color: #744542;
    margin-bottom: 15px;
}

/* Table wrapper for horizontal scroll */
.table-wrapper {
    overflow-x: auto;
}

#orderHistoryTable {
    width: 100%;
    border-collapse: collapse;
    min-width: 900px;
}

#orderHistoryTable th, #orderHistoryTable td {
    padding: 8px;
    border: 1px solid #ddd;
    text-align: center;
    white-space: nowrap;
}

#orderHistoryTable th {
    background: #744542;
    color: white;
    font-size: 13px;
}

#orderHistoryTable tr:nth-child(even) {
    background: #f9f9f9;
}

.status-badge {
    padding: 3px 6px;
    border-radius: 4px;
    color: #fff;
    font-weight: bold;
    font-size: 12px;
}

/* Responsive adjustments */
@media (max-width: 900px) {
    .order-history-container {
        padding: 10px;
    }
    #orderHistoryTable th, #orderHistoryTable td {
        font-size: 12px;
        padding: 6px 8px;
    }
}

@media (max-width: 600px) {
    #orderHistoryTable {
        min-width: 700px;
    }
    .order-history-container h2 {
        font-size: 18px;
    }
}
</style>

<!-- ====== JS (Optional search/filter) ====== -->
<script>
function filterOrders(keyword) {
    const table = document.getElementById('orderHistoryTable');
    const tr = table.getElementsByTagName('tr');

    for (let i = 1; i < tr.length; i++) {
        let tdArr = tr[i].getElementsByTagName('td');
        let show = false;
        for (let j = 0; j < tdArr.length; j++) {
            if (tdArr[j].innerText.toLowerCase().indexOf(keyword.toLowerCase()) > -1) {
                show = true;
                break;
            }
        }
        tr[i].style.display = show ? '' : 'none';
    }
}
</script>
