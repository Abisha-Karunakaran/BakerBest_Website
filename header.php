<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BakerBest</title>

    <style>
        /* RESET */
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            overflow-x: hidden;
        }

        /* HEADER */
        .header {
            width: 100%;
            background: #ffffff;
            border-bottom: 1px solid #eee;
            padding: 15px 50px;
            position: sticky;
            top: 0;
            z-index: 2000;
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* LOGO */
        .logo-text {
            font-size: 24px;
            font-weight: 600;
            color: #934C47;
            cursor: pointer;
        }

        /* NAVIGATION (DESKTOP) */
        .navbar {
            display: flex;
            list-style: none;
            align-items: center;
            gap: 30px;
        }

        .navbar li a {
            text-decoration: none;
            font-weight: 600;
            color: #934C47;
            transition: .3s;
        }

        .navbar li a:hover {
            color: #8a8a8a;
        }

        /* LOGIN BUTTON */
        .login-btn {
            padding: 8px 15px;
            background: #934C47;
            color: white !important;
            border-radius: 6px;
            font-weight: 500;
        }

        /* USER DROPDOWN */
        .user-dropdown {
            position: relative;
        }

        .user-icon {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ddd;
            padding: 3px;
            cursor: pointer;
        }

        .dropdown-menu {
            position: absolute;
            right: 0;
            top: 45px;
            background: #fff;
            width: 180px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
            border-radius: 8px;
            padding: 10px 0;
            display: none; /* hidden by default */
            list-style: none;
            z-index: 9999;
        }

        .dropdown-menu li {
            padding: 10px 18px;
            font-size: 14px;
            border-bottom: 1px solid #eee;
        }

        .dropdown-menu li:last-child {
            border-bottom:none;
        }

        /* MOBILE MENU ICON */
        .mobile-menu-icon {
            display: none;
            font-size: 30px;
            cursor: pointer;
            color: #934C47;
        }

        /* MOBILE SIDEBAR */
        .mobile-sidebar {
            position: fixed;
            top: 0;
            left: -280px;
            width: 260px;
            height: 100%;
            background: #ffffff;
            box-shadow: 2px 0 10px rgba(0,0,0,0.15);
            padding: 30px 20px;
            transition: 0.3s;
            z-index: 3000;
        }

        .mobile-sidebar.active {
            left: 0;
        }

        .close-btn {
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            text-align: right;
            color: #934C47;
        }

        .mobile-sidebar ul {
            list-style: none;
            margin-top: 20px;
        }

        .mobile-sidebar ul li {
            padding: 15px 5px;
        }

        .mobile-sidebar ul li a {
            text-decoration: none;
            font-size: 17px;
            font-weight: 600;
            color: #934C47;
        }

        /* RESPONSIVE */
        @media (max-width: 820px){
            .navbar {
                display: none;
            }

            .mobile-menu-icon {
                display: block;
            }

            .header {
                padding: 15px 20px;
            }
        }

        /* Optional: Desktop hover */
        @media (min-width: 821px){
            .user-dropdown:hover .dropdown-menu {
                display: block;
            }
        }
    </style>
</head>

<body>

<!-- MOBILE SIDEBAR -->
<div class="mobile-sidebar" id="mobileSidebar">
    <div class="close-btn" onclick="toggleSidebar()">×</div>

    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="menu.php">Menu</a></li>
        <li><a href="order.php">Order</a></li>
        <li><a href="gallery.php">Gallery</a></li>
        <li><a href="contact.php">Contact</a></li>

        <?php if (!isset($_SESSION['user_id'])) : ?>
            <li><a href="login.php" class="login-btn">Login</a></li>
        <?php else: ?>
            <li><strong><?php echo $_SESSION['user_name']; ?></strong></li>
            <li><?php echo $_SESSION['user_email']; ?></li>
            <li><a href="logout.php">Logout</a></li>
        <?php endif; ?>
    </ul>
</div>

<!-- HEADER -->
<header class="header">
    <nav class="nav-container">

        <h2 class="logo-text" onclick="location.href='index.php'">🍞BakerBest</h2>

        <!-- MOBILE MENU ICON -->
        <div class="mobile-menu-icon" onclick="toggleSidebar()">☰</div>

        <!-- DESKTOP NAV -->
        <ul class="navbar">

            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="menu.php">Menu</a></li>
            <li><a href="order.php">Order</a></li>
            <li><a href="gallery.php">Gallery</a></li>
            <li><a href="contact.php">Contact</a></li>

            <?php if (!isset($_SESSION['user_id'])) : ?>
                <li><a href="login.php" class="login-btn">Login</a></li>

            <?php else : ?>
                <li class="user-dropdown">
                    <img src="assets/user.png" class="user-icon">

                    <ul class="dropdown-menu">
                        <li><strong><?php echo $_SESSION['user_name']; ?></strong></li>
                        <li><?php echo $_SESSION['user_email']; ?></li>
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                </li>
            <?php endif; ?>
        </ul>

    </nav>
</header>

<script>
function toggleSidebar() {
    document.getElementById("mobileSidebar").classList.toggle("active");
}

// ====== User Dropdown Toggle (Mobile Friendly) ======
document.addEventListener('DOMContentLoaded', function() {
    const dropdown = document.querySelector('.user-dropdown');
    if (!dropdown) return;

    const menu = dropdown.querySelector('.dropdown-menu');

    dropdown.querySelector('.user-icon').addEventListener('click', function(e){
        e.stopPropagation(); // Prevent closing immediately
        menu.classList.toggle('active');
        menu.style.display = menu.classList.contains('active') ? 'block' : 'none';
    });

    // Close dropdown if clicked outside
    document.addEventListener('click', function(){
        menu.classList.remove('active');
        menu.style.display = 'none';
    });
});
</script>
</body>
</html>
