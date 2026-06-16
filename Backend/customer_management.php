<?php
include_once "admin_header.php";

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

// Connect to database
$conn = mysqli_connect("localhost", "root", "root", "baker_best");
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch all registered customers
$query = "SELECT * FROM users ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<div class="main-content">
    <h2 style="text-align:center; color:#744542; margin-top:40px;">Customer Management</h2>

    <div class="table-wrapper">
        <?php if(mysqli_num_rows($result) === 0) { ?>
            <p style="text-align:center;">No registered customers found.</p>
        <?php } else { ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Registration Date</th>
                </tr>

                <?php while($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['phone'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($row['address'] ?? '-') ?></td>
                        <td><?= $row['created_at'] ?></td>
                    </tr>
                <?php } ?>
            </table>
        <?php } ?>
    </div>
</div>

<?php
mysqli_free_result($result);
mysqli_close($conn);
?>

<!-- ====== CSS ====== -->
<style>
.main-content {
    max-width: 1200px;
    margin: 20px auto;
    padding: 20px;
}

.table-wrapper {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    overflow-x: auto;
    font-size: 14px;
    margin-left: 150px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

th, td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: center;
}

th {
    background: #744542;
    color: white;
}

tr:nth-child(even) {
    background: #f9f9f9;
}
</style>
