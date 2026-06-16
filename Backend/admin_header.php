<?php
// Safe session start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BakerBest Admin</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
/* THEME COLORS */
:root{
    --bg: #C39F97;
    --text: #744542;
    --white: #ffffff;
    --card-radius: 14px;
    --shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* RESET */
*{
    box-sizing: border-box;
}
html, body {
    margin: 0;
    padding: 0;
    width:100%;
    overflow-x: hidden;
    font-family: "Poppins", sans-serif;
    background: var(--bg);
}

/* HEADER */
.admin-header {
    width: 100%;
    background: var(--white);
    padding: 18px 25px;
    display: flex;
    align-items: center;
    box-shadow: var(--shadow);
    position: sticky;
    top: 0;
    z-index: 1000;
    position: fixed;
}

.admin-header h2 {
    color: var(--text);
    margin: 0;
    font-size: 22px;
    font-weight: 600;
}

.admin-header .right {
    margin-left: auto;
    display: flex;
    gap: 22px;
    align-items: center;
}

.admin-header i {
    font-size: 18px;
    color: var(--text);
}

/* HAMBURGER (mobile only) */
.menu-btn {
    display: none;
    background: transparent;
    border: none;
    font-size: 22px;
    color: var(--text);
    margin-right: 10px;
}

/* LAYOUT */
.layout {
    display: flex;
    min-height: calc(100vh - 70px);
}

/* SIDEBAR */
.sidebar {
    width: 270px;
    background: var(--text);
    min-height: 100vh;
    padding: 25px 15px;
    position: fixed;
    top: 60px;
    left: 0;
    transition: transform .30s ease;
    transform: translateX(0); /* Default visible */
}

.sidebar.hidden {
    transform: translateX(-260px);
}

.sidebar h3 {
    color: var(--white);
    text-align: center;
    margin-bottom: 25px;
}

.sidebar a {
    display: block;
    padding: 12px 16px;
    margin: 8px 0;
    background: rgba(255,255,255,0.2);
    border-radius: 10px;
    color: var(--white);
    text-decoration: none;
    font-weight: 500;
    transition: 0.3s;
}

.sidebar a:hover {
    background: rgba(255,255,255,0.35);
}

/* MAIN CONTENT */
.main-content {
    flex: 1;
    padding: 25px;
    margin-left: 240px;
}

/* RESPONSIVE */
@media (max-width: 900px){
    .menu-btn {
        display: block;
    }
    .sidebar {
        transform: translateX(-260px);
    }
    .sidebar.show {
        transform: translateX(0);
    }
    .main-content {
        margin-left: 0;
    }
}
</style>

<script>
function toggleSidebar(){
    const bar = document.getElementById("sidebar");
    bar.classList.toggle("show");
}
</script>

</head>

<body>

<!-- HEADER -->
<div class="admin-header">
    <button class="menu-btn" onclick="toggleSidebar()">
        <i class="fa fa-bars"></i>
    </button>

    <h2>BakerBest Admin Panel</h2>

    <div class="right">
        <i class="fa fa-bell"></i>
        <span style="color: var(--text); font-weight: 600;">
            <?php echo $_SESSION['admin_username'] ?? "Admin"; ?>
        </span>
    </div>
</div>

<div class="layout">

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">
    <h3>Navigation</h3>

    <a href="admin_dashboard.php">📊 Dashboard</a>
    <a href="menu_management.php">🍞 Menu Management</a>
    <a href="category_management.php">🏷 Category Management</a>
    <a href="order_management.php">📦 Order Management</a>
    <a href="customer_management.php">👥 Customer Management</a>
    <a href="admin_messages.php">💬 Customer Support </a>
    <a href="gallery_management.php">🖼 Gallery Management</a>

    <a href="admin_logout.php" style="margin-top:20px; background: #ffffff; color: #744542;">🚪 Logout</a>
</div>
