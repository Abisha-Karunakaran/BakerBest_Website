<?php
require_once "Backend/db.php";  // Make sure this path is correct

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]); // Plain text
    $phone = trim($_POST["phone"]);
    $address = trim($_POST["address"]);

    if (!$conn) {
        die("Database connection failed!");
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error = "Email already registered!";
    } else {
        // Insert without hashing
        $insert_stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, address, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $insert_stmt->bind_param("sssss", $name, $email, $password, $phone, $address);

        if ($insert_stmt->execute()) {
            $success = "Account created successfully! Redirecting to login...";
            header("refresh:1.5; url=login.php");
        } else {
            $error = "Something went wrong: " . $conn->error;
        }

        $insert_stmt->close();
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Signup - BakerBest</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f2ddc5;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .signup-container {
            width: 400px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .signup-container h2 {
            margin-bottom: 20px;
            color: #4a2c2a;
        }
        .input-field {
            width: 80%;
            padding: 12px;
            margin: 8px 0;
            border-radius: 5px;
            border: 1px solid #d1c5b5;
            font-size: 15px;
        }
        .signup-btn {
            width: 100%;
            padding: 12px;
            background: #9d6d65ff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            font-size: 16px;
        }
        .login-link {
            margin-top: 15px;
            font-size: 14px;
        }
        a { color: #9d6d65ff; text-decoration: none; font-weight: bold; }
        .error { color: red; margin-bottom: 10px; }
        .success { color: green; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="signup-container">
        <h2>Create Account</h2>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="text" name="name" class="input-field" placeholder="Full Name" required>
            <input type="email" name="email" class="input-field" placeholder="Email" required>
            <input type="password" name="password" class="input-field" placeholder="Password" required>
            <input type="text" name="phone" class="input-field" placeholder="Phone Number" required>
            <input type="text" name="address" class="input-field" placeholder="Address" required>
            <button type="submit" class="signup-btn">Signup</button>
        </form>

        <p class="login-link">
            Already have an account? <a href="login.php">Login</a>
        </p>
    </div>
</body>
</html>
