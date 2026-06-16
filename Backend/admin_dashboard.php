<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

include "admin_header.php";
include "db.php";
?>

<style>
/* ===== DASHBOARD STYLING ===== */
.dashboard-title {
    color: #59331D;
    font-size: 32px;
    font-weight: 700;
    margin-top: 40px;
    margin-left: 40px;
}

.card-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px,1fr));
    gap: 20px;
    margin: 20px 40px;
}

.card {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: flex-start;
    padding: 25px 30px;
    border-radius: var(--card-radius);
    box-shadow: var(--shadow);
    background: var(--white);
    position: relative;
    overflow: hidden;
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
}

.card h4 {
    margin: 0;
    font-size: 20px;
    color: var(--text);
}

.card p {
    margin: 5px 0 0;
    font-size: 18px;
    color: #5a3d36;
    font-weight: bold;
}

/* Icon in card */
.card i {
    position: absolute;
    top: 15px;
    right: 20px;
    font-size: 40px;
    color: rgba(0,0,0,0.1);
}

/* Orders Table */
.orders-table {
    width: 95%;
    margin: 30px auto;
    border-collapse: collapse;
    font-size: 14px;
}

.orders-table th, .orders-table td {
    padding: 10px 12px;
    border: 1px solid #ddd;
    text-align: left;
}

.orders-table th {
    background: #744542;
    color: #fff;
    font-weight: 600;
}

.orders-table tr:nth-child(even) {
    background: #f9f9f9;
}

/* Status badges */
.status-badge {
    padding: 5px 10px;
    border-radius: 12px;
    color: #fff;
    font-size: 12px;
    font-weight: 600;
}

.Pending { background: #f39c12; }
.Processing { background: #3498db; }
.Ready { background: #9b59b6; }
.Completed { background: #27ae60; }
.Cancelled { background: #c0392b; }

/* Responsive */
@media(max-width:900px){
    .card-grid {
        grid-template-columns: 1fr 1fr;
    }
}
@media(max-width:600px){
    .card-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php
// Fetch stats
$order_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total_orders FROM orders"))['total_orders'] ?? 0;
$pending_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as pending_orders FROM orders WHERE status='Pending'"))['pending_orders'] ?? 0;
$completed_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as completed_orders FROM orders WHERE status='Completed'"))['completed_orders'] ?? 0;
$today_orders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as today_orders FROM orders WHERE DATE(created_at) = CURDATE()"))['today_orders'] ?? 0;
$revenue = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_price) as revenue FROM orders WHERE status='Completed'"))['revenue'] ?? 0;
$revenue = number_format($revenue,2);
$customer_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total_customers FROM users"))['total_customers'] ?? 0;
$product_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total_products FROM menu_items"))['total_products'] ?? 0;
?>

<div class="main-content">

<h1 class="dashboard-title">Dashboard Overview</h1>

<div class="card-grid">
    <div class="card">
        <i class="fas fa-shopping-cart"></i>
        <h4>Total Orders</h4>
        <p><?= $order_count ?></p>
    </div>

    <div class="card">
        <i class="fas fa-clock"></i>
        <h4>Pending Orders</h4>
        <p><?= $pending_count ?></p>
    </div>

    <div class="card">
        <i class="fas fa-check-circle"></i>
        <h4>Completed Orders</h4>
        <p><?= $completed_count ?></p>
    </div>

    <div class="card">
        <i class="fas fa-calendar-day"></i>
        <h4>Today's Orders</h4>
        <p><?= $today_orders ?></p>
    </div>

    <div class="card">
        <i class="fas fa-dollar-sign"></i>
        <h4>Total Revenue</h4>
        <p>Rs<?= $revenue ?></p>
    </div>

    <div class="card">
        <i class="fas fa-users"></i>
        <h4>Total Customers</h4>
        <p><?= $customer_count ?></p>
    </div>

    <div class="card">
        <i class="fas fa-box-open"></i>
        <h4>Live Products</h4>
        <p><?= $product_count ?></p>
    </div>
</div>

<h2 class="dashboard-title">Last 5 Orders</h2>
<table class="orders-table">
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Total Price</th>
            <th>Status</th>
            <th>Pickup Date</th>
            <th>Pickup Time</th>
            <th>Ordered At</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $last_orders = mysqli_query($conn, "SELECT * FROM orders ORDER BY created_at DESC LIMIT 5");
        while($order = mysqli_fetch_assoc($last_orders)):
        ?>
        <tr>
            <td><?= $order['id'] ?></td>
            <td><?= htmlspecialchars($order['customer_name']) ?><br><?= htmlspecialchars($order['customer_email']) ?></td>
            <td>Rs <?= number_format($order['total_price'],2) ?></td>
            <td>
                <span class="status-badge <?= str_replace(' ','',$order['status']) ?>">
                    <?= $order['status'] ?>
                </span>
            </td>
            <td><?= $order['pickup_date'] ?></td>
            <td><?= $order['pickup_time'] ?></td>
            <td><?= $order['created_at'] ?></td>
        </tr>
        <?php endwhile; ?> 
    </tbody>
</table>

</div>

<!-- Include Font Awesome for icons -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
