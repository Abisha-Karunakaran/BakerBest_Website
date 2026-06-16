<?php
// category_management.php

// safe session start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// admin auth
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// include header (contains the admin header + sidebar)
include 'admin_header.php';

// DB connection (adjust if needed)
$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = "root";
$DB_NAME = "baker_best";

$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if (!$conn) {
    die("DB Connection failed: " . mysqli_connect_error());
}

// feedback
$notice = "";
$error = "";

/* -------------------------
   Add Category
   ------------------------- */
if (isset($_POST['add_category'])) {
    $name = trim($_POST['category_name']);
    if ($name === "") {
        $error = "Category name cannot be empty.";
    } else {
        $stmt = $conn->prepare("INSERT INTO menu_categories (category_name, created_at) VALUES (?, NOW())");
        $stmt->bind_param("s", $name);
        if ($stmt->execute()) {
            $notice = "Category added.";
        } else {
            $error = "Database error while adding category.";
        }
        $stmt->close();
    }
}

/* -------------------------
   Edit Category
   ------------------------- */
if (isset($_POST['edit_category'])) {
    $id = intval($_POST['id']);
    $name = trim($_POST['category_name']);
    if ($name === "") {
        $error = "Category name cannot be empty.";
    } else {
        $stmt = $conn->prepare("UPDATE menu_categories SET category_name=? WHERE id=?");
        $stmt->bind_param("si", $name, $id);
        if ($stmt->execute()) {
            $notice = "Category updated.";
        } else {
            $error = "Update failed.";
        }
        $stmt->close();
    }
}

/* -------------------------
   Delete Category (GET)
   ------------------------- */
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // -- delete (note: if menu_items.category_id has foreign key with SET NULL, ok)
    $stmt = $conn->prepare("DELETE FROM menu_categories WHERE id=?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $notice = "Category deleted.";
    } else {
        $error = "Unable to delete category.";
    }
    $stmt->close();
}

/* -------------------------
   Fetch categories
   ------------------------- */
$cats_res = mysqli_query($conn, "SELECT id, category_name, created_at FROM menu_categories ORDER BY created_at DESC");

?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Category Management - Admin</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<style>
:root{
  --accent:#744542ff;
  --bg: #C39F97;
  --card:#ffeedd;
  --card-2:#ffffff;
  --muted:#6b584f;
  --shadow: 0 6px 18px rgba(0,0,0,0.08);
  --radius:12px;
}

*{box-sizing:border-box}
body{
  margin:0;
  font-family: 'Poppins', sans-serif;
  background: var(--bg);
  color: var(--accent);
}

/* container aligns with admin_header sidebar (sidebar is 240-260px wide) */
.container {
  max-width: 1200px;
  margin-left: 260px; /* reserve space for admin sidebar */
  padding: 28px;
}

/* page header row */
.page-head{
  display:flex;
  justify-content:space-between;
  align-items:center;
  gap:12px;
  margin-bottom:18px;
}
.page-head h1{ margin:0; font-size:24px; color:var(--accent) }
.page-head .back-link { text-decoration:none; color:var(--accent); font-weight:600 }

/* layout grid: left form, right table */
.grid {
  display:grid;
  grid-template-columns: 380px 1fr;
  gap:18px;
  align-items:start;
}

/* card */
.card {
  background: var(--card);
  padding:18px;
  border-radius: var(--radius);
  box-shadow: var(--shadow);
}

/* input styles */
.label { display:block; margin-bottom:6px; font-weight:600; color:var(--muted); }
input[type="text"], textarea, select {
  width:100%;
  padding:10px 12px;
  border-radius:8px;
  border:1px solid rgba(0,0,0,0.06);
  margin-bottom:12px;
  font-size:14px;
  outline:none;
  background:var(--card-2);
}
textarea { resize:vertical; min-height:100px; }

.btn {
  display:inline-block;
  padding:10px 14px;
  background: var(--accent);
  color:#fff;
  border:0;
  border-radius:8px;
  cursor:pointer;
  font-weight:700;
}
.btn--muted { background:#e6e6e6; color:#333; }

/* table card (right) */
.table-card {
  background: var(--card);
  padding:14px;
  border-radius: var(--radius);
  box-shadow: var(--shadow);
  overflow-x:auto;
}

.table-card table{ width:100%; border-collapse:collapse; min-width:720px; }
.table-card th, .table-card td {
  padding:12px 10px;
  border-bottom:1px solid rgba(0,0,0,0.04);
  text-align:left;
}
.table-card th { background:var(--accent); color:#fff; font-weight:700; }

/* small helper */
.small { font-size:13px; color:var(--muted); }

/* messages */
.notice { background:#eaf7ea; color:#0b6b26; padding:10px 12px; border-radius:8px; margin-bottom:12px; }
.error { background:#ffecec; color:#a71d1d; padding:10px 12px; border-radius:8px; margin-bottom:12px; }

/* responsive: stack when small */
@media(max-width:980px){
  .container { margin-left:0; padding:18px; }
  .grid { grid-template-columns: 1fr; }
  .table-card table { min-width:100%; }
}
</style>
</head>
<body>

<div class="container">

  <div class="page-head">
    <h1>Category Management</h1>
    <a class="back-link" href="admin_dashboard.php">← Back to Dashboard</a>
  </div>

  <?php if($notice): ?>
    <div class="notice"><?= htmlspecialchars($notice) ?></div>
  <?php endif; ?>

  <?php if($error): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <div class="grid">

    <!-- LEFT: Add / Edit form -->
    <div class="card">
      <h3 style="margin-top:0;color:var(--accent)"><?= isset($_GET['edit']) ? "Edit Category" : "Add Category" ?></h3>

      <!-- Add form -->
      <form id="addForm" method="POST" style="<?= isset($_GET['edit']) ? 'display:none;' : '' ?>">
        <label class="label">Category Name</label>
        <input type="text" name="category_name" placeholder="e.g. Cakes, Breads..." required>

        <button type="submit" name="add_category" class="btn">Add Category</button>
      </form>

      <!-- Edit form -->
      <form id="editForm" method="POST" style="<?= isset($_GET['edit']) ? '' : 'display:none;' ?>">
        <input type="hidden" id="edit_id" name="id" value="">
        <label class="label">Category Name</label>
        <input type="text" id="edit_name" name="category_name" required>

        <div style="display:flex;gap:10px;margin-top:6px;">
          <button type="submit" name="edit_category" class="btn">Save</button>
          <button type="button" class="btn btn--muted" onclick="cancelEdit()">Cancel</button>
        </div>
      </form>
    </div>

    <!-- RIGHT: Category list -->
    <div class="table-card">
      <h3 style="margin:0 0 12px 0;color:var(--accent)">Existing Categories</h3>

      <table>
        <thead>
          <tr>
            <th style="width:80px">ID</th>
            <th>Category</th>
            <th>Created</th>
            <th style="width:140px">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($c = mysqli_fetch_assoc($cats_res)): ?>
            <tr>
              <td><?= (int)$c['id'] ?></td>
              <td><?= htmlspecialchars($c['category_name']) ?></td>
              <td class="small"><?= htmlspecialchars($c['created_at']) ?></td>
              <td>
                <a href="javascript:void(0)" onclick="startEdit(<?= (int)$c['id'] ?>, '<?= addslashes(htmlspecialchars($c['category_name'])) ?>')" style="color:var(--accent);font-weight:700;margin-right:12px">Edit</a>
                <a href="?delete=<?= (int)$c['id'] ?>" onclick="return confirm('Delete category? This will not delete items but set their category to NULL.')" style="color:#b33a2c;font-weight:700">Delete</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>

    </div>
  </div>
</div>

<script>
  // Start editing a category: populate edit form and show it
  function startEdit(id, name) {
    document.getElementById('addForm').style.display = 'none';
    document.getElementById('editForm').style.display = 'block';
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_name').value = name;
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  function cancelEdit() {
    document.getElementById('editForm').style.display = 'none';
    document.getElementById('addForm').style.display = 'block';
    document.getElementById('edit_id').value = '';
    document.getElementById('edit_name').value = '';
  }

  // If page loaded with ?edit=ID param (optional), auto open edit (server side could do this but here is client fallback)
  (function() {
    const url = new URL(window.location.href);
    const edit = url.searchParams.get('edit');
    if (edit) {
      // try to find the row and parse the name from the DOM
      const row = document.querySelector('tbody tr td:first-child + td');
      // Instead of complex DOM search, rely on server redirecting to ?edit=id with prefilled values
      // For simplicity, we will not auto-fill here if server didn't.
    }
  })();
</script>

<?php
mysqli_close($conn);

?>
