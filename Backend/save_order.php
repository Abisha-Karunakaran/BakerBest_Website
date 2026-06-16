<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'db.php';
session_start();

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    echo json_encode(['status'=>'error','msg'=>'No data received']);
    exit();
}

// Required fields
$cart = $input['cart'] ?? [];
$pickup_date = $input['pickup_date'] ?? '';
$pickup_time = $input['pickup_time'] ?? '';
$payment_method = $input['payment_method'] ?? '';
$customer_name = $input['customer_name'] ?? '';
$customer_email = $input['customer_email'] ?? '';

// Validate
if (empty($cart) || empty($pickup_date) || empty($pickup_time) || empty($payment_method) || empty($customer_name)) {
    echo json_encode(['status'=>'error','msg'=>'Missing required fields']);
    exit();
}

// Calculate total price
$total_price = 0;
foreach ($cart as $item) {
    $price = floatval($item['price'] ?? 0);
    $qty = intval($item['qty'] ?? 0);
    $total_price += $price * $qty;
}

// Save order
$order_data_json = json_encode($cart, JSON_UNESCAPED_UNICODE);

$stmt = $conn->prepare("
    INSERT INTO orders 
    (customer_name, customer_email, order_data, total_price, pickup_date, pickup_time, payment_method, status, created_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, 'Pending', NOW())
");
$stmt->bind_param("sssisss", $customer_name, $customer_email, $order_data_json, $total_price, $pickup_date, $pickup_time, $payment_method);

if ($stmt->execute()) {
    echo json_encode(['status'=>'success','order_id'=>$stmt->insert_id]);
} else {
    echo json_encode(['status'=>'error','msg'=>$stmt->error]);
}

$stmt->close();
$conn->close();
