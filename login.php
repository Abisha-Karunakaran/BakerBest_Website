<?php
session_start();
require_once "Backend/db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Check DB connection
    if (!$conn) {
        die("DB connection failed!");
    }

    // Find user by email
    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

    if (mysqli_num_rows($query) == 1) {
        $user = mysqli_fetch_assoc($query);

        // Since passwords are NOT hashed, compare directly
        if ($password === $user["password"]) {
            // Save session values
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["user_name"] = $user["name"];
            $_SESSION["user_email"] = $user["email"];

            header("Location: index.php");
            exit();
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "Email not found!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - BakerBest</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f2ddc5;
            font-family: Arial, sans-serif;
            height: 100vh;

            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            width: 360px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }

        .login-container h2 {
            margin-bottom: 20px;
            color: #4a2c2a;
        }

        .input-field {
            width: 80%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #d1c5b5;
            font-size: 15px;
           

        }

        .login-btn {
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

        .login-btn:hover {
            opacity: 0.9;
        }

        .signup-link {
            margin-top: 15px;
            font-size: 14px;
        }

        a {
            color: #9d6d65ff;
            text-decoration: none;
            font-weight: bold;
        }

        .error {
            color: #9d6d65ff;
            margin-bottom: 10px;
            font-size: 14px;
        }
    </style>

</head>
<body>

    <div class="login-container">

        <h2>Login</h2>

        <?php if ($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <input type="email" name="email" class="input-field" placeholder="Email" required>
            <input type="password" name="password" class="input-field" placeholder="Password" required>

            <button type="submit" class="login-btn">Login</button>
        </form>

        <p class="signup-link">
            Don't have an account? <a href="signup.php">Signup</a>
        </p>

    </div>

</body>
</html>
