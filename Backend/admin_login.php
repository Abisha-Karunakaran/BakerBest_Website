<?php
session_start();

// DATABASE CONNECTION
$conn = mysqli_connect("localhost","root","root", "baker_best");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$error = "";

// LOGIN CHECK
if (isset($_POST['login_btn'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM admin_users WHERE username='$username' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        // ✔ Check hashed password
        if ($password === $row['password']) {

            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $row['username'];

            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "Username not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | BakerBest</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            background: #F7E9D7;
            font-family: 'Poppins', sans-serif;
            margin: 0;
        }

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-box {
            background: #fff;
            width: 420px;
            padding: 35px 30px;
            border-radius: 14px;
            box-shadow: 0 4px 18px rgba(0,0,0,0.1);
            text-align: center;
        }

        .login-box h2 {
            margin-bottom: 25px;
            color: #4a2f1c;
        }

        .form-box {
            width: 80%;
            margin: 0 auto;
            text-align: left;
        }

        .form-box label {
            display: block;
            width: 100%;
            margin-bottom: 6px;
            font-weight: 500;
            color: #4a2f1c;
        }

        .form-box input {
            width: 310px;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #c8b8a8;
            margin-bottom: 16px;
            background: #fff7ef;
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            background: #4a2f1c;
            border: none;
            color: #fff;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 5px;
            transition: 0.3s;
        }

        .login-btn:hover {
            background: #331f13;
        }

        .reset-link {
            display: block;
            margin-top: 12px;
            color: #4a2f1c;
            text-decoration: none;
        }

        .reset-link:hover {
            text-decoration: underline;
        }

        .error-msg {
            background: #ffdddd;
            padding: 10px;
            color: #bb0000;
            margin-bottom: 10px;
            border-radius: 5px;
            font-size: 14px;
            text-align: center;
        }

        @media (max-width: 480px) {
            .login-box {
                width: 90%;
            }
            .form-box {
                width: 90%;
            }
        }
    </style>
</head>

<body>

<div class="login-container">
    <div class="login-box">
        <h2>Admin Login</h2>

        <?php if ($error != ""): ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-box">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter username" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter password" required>

                <button type="submit" name="login_btn" class="login-btn">Login</button>

               
            </div>
        </form>
    </div>
</div>

</body>
</html>
