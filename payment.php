<?php
// payment.php - process checkout and create order record
if (session_status() === PHP_SESSION_NONE) session_start();

$DB_HOST='localhost'; $DB_USER='root'; $DB_PASS='root'; $DB_NAME='baker_best';
$conn = mysqli_connect($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME);
if (!$conn) die("DB connect error: ".mysqli_connect_error());

$session_key = session_id();
$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: order.php");
    exit;
}

// basic validation
$cust_name = trim($_POST['cust_name'] ?? '');
$cust_phone = trim($_POST['cust_phone'] ?? '');
$cust_email = trim($_POST['cust_email'] ?? '');
$pickup_datetime = trim($_POST['pickup_datetime'] ?? '');
$notes = trim($_POST['notes'] ?? '');
$payment_method = in_array($_POST['payment_method'] ?? 'pickup', ['pickup','card']) ? $_POST['payment_method'] : 'pickup';
$discount_percent = floatval($_POST['discount_percent'] ?? 0.0);

$errors = [];
if ($cust_name === '') $errors[] = "Enter full name";
if ($cust_phone === '') $errors[] = "Enter phone";

if (!empty($errors)) {
    // redirect back with simple error (improve as needed)
    $_SESSION['checkout_errors'] = $errors;
    header("Location: order.php");
    exit;
}

// fetch cart items for this user/session
if ($user_id !== null) {
    $stmt = $conn->prepare("SELECT item_id, item_name, price, qty FROM cart_items WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
} else {
    $stmt = $conn->prepare("SELECT item_id, item_name, price, qty FROM cart_items WHERE session_key = ?");
    $stmt->bind_param("s", $session_key);
}
$stmt->execute();
$res = $stmt->get_result();

$items = [];
$subtotal = 0.0;
while ($r = $res->fetch_assoc()) {
    $items[] = $r;
    $subtotal += floatval($r['price']) * intval($r['qty']);
}
$stmt->close();

if (empty($items)) {
    $_SESSION['checkout_errors'] = ["Cart is empty"];
    header("Location: order.php");
    exit;
}

$discount_amount = round($subtotal * ($discount_percent / 100.0), 2);
$total = round($subtotal - $discount_amount, 2);

// begin transaction
mysqli_begin_transaction($conn);
try {
    // insert order
    if ($user_id !== null) {
        $stmt = $conn->prepare("INSERT INTO orders (user_id, customer_name, phone, total, discount_percent, payment_method, status, pickup_datetime, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $status = ($payment_method === 'card') ? 'Processing' : 'Ready to Pickup';
        $stmt->bind_param("issd d s s s", $user_id, $cust_name, $cust_phone, $total, $discount_percent, $payment_method, $status, $pickup_datetime, $notes);
        // Because binding complex types is messy across versions, use safer escaped query:
        $safe_name = $conn->real_escape_string($cust_name);
        $safe_phone = $conn->real_escape_string($cust_phone);
        $safe_notes = $conn->real_escape_string($notes);
        $safe_status = $conn->real_escape_string($status);
        $safe_method = $conn->real_escape_string($payment_method);
        $q = "INSERT INTO orders (user_id, customer_name, phone, total, discount_percent, payment_method, status, pickup_datetime, notes) VALUES ($user_id, '$safe_name', '$safe_phone', $total, $discount_percent, '$safe_method', '$safe_status', " . ($pickup_datetime? "'".$conn->real_escape_string($pickup_datetime)."'":"NULL") . ", '$safe_notes')";
        if (!mysqli_query($conn, $q)) throw new Exception(mysqli_error($conn));
        $order_id = mysqli_insert_id($conn);
    } else {
        $safe_name = $conn->real_escape_string($cust_name);
        $safe_phone = $conn->real_escape_string($cust_phone);
        $safe_email = $conn->real_escape_string($cust_email);
        $safe_notes = $conn->real_escape_string($notes);
        $safe_status = ($payment_method === 'card') ? 'Processing' : 'Ready to Pickup';
        $q = "INSERT INTO orders (guest_email, customer_name, phone, total, discount_percent, payment_method, status, pickup_datetime, notes) VALUES ('$safe_email', '$safe_name', '$safe_phone', $total, $discount_percent, '$payment_method', '$safe_status', " . ($pickup_datetime? "'".$conn->real_escape_string($pickup_datetime)."'":"NULL") . ", '$safe_notes')";
        if (!mysqli_query($conn, $q)) throw new Exception(mysqli_error($conn));
        $order_id = mysqli_insert_id($conn);
    }

    // insert order_items
    $stmtIns = $conn->prepare("INSERT INTO order_items (order_id, item_id, item_name, price, qty) VALUES (?, ?, ?, ?, ?)");
    foreach ($items as $it) {
        $iid = intval($it['item_id']);
        $iname = $conn->real_escape_string($it['item_name']);
        $iprice = floatval($it['price']);
        $iqty = intval($it['qty']);
        $q2 = "INSERT INTO order_items (order_id, item_id, item_name, price, qty) VALUES ($order_id, $iid, '$iname', $iprice, $iqty)";
        if (!mysqli_query($conn, $q2)) throw new Exception(mysqli_error($conn));
    }

    // clear cart items for this session/user
    if ($user_id !== null) {
        $del = $conn->prepare("DELETE FROM cart_items WHERE user_id = ?");
        $del->bind_param("i", $user_id);
        $del->execute();
        $del->close();
    } else {
        $del = $conn->prepare("DELETE FROM cart_items WHERE session_key = ?");
        $del->bind_param("s", $session_key);
        $del->execute();
        $del->close();
    }

    mysqli_commit($conn);

    // redirect to success page
    header("Location: success.php?order=" . intval($order_id));
    exit;

} catch (Exception $ex) {
    mysqli_rollback($conn);
    $_SESSION['checkout_errors'] = ["Failed to create order: " . $ex->getMessage()];
    header("Location: order.php");
    exit;
}
