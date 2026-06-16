<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

// Enable errors for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// DB connection
$conn = mysqli_connect("localhost", "root", "root", "baker_best");
if (!$conn) die("DB Connection Failed: " . mysqli_connect_error());

// ---------------------------------------------------------
// DELETE MENU ITEM
// ---------------------------------------------------------
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $q = mysqli_query($conn, "SELECT image FROM menu_items WHERE id=$id");
    $img = mysqli_fetch_assoc($q)['image'];

    if ($img && file_exists($img)) unlink($img);

    mysqli_query($conn, "DELETE FROM menu_items WHERE id=$id");
    header("Location: menu_management.php?msg=deleted");
    exit();
}

// ---------------------------------------------------------
// ADD / EDIT MENU ITEM
// ---------------------------------------------------------
$msg = "";

if (isset($_POST['save'])) {
    $id = intval($_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = floatval($_POST['price']);
    $category = intval($_POST['category']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $discount = floatval($_POST['discount']); // New Discount Field

    // Image upload
    $imagePath = "";
    if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
        $targetDir = "assets/menu/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $fileName = time() . "_" . basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . $fileName;

        move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);
        $imagePath = $targetFile;
    }

    if ($id == 0) {
        // INSERT NEW
        $sql = "INSERT INTO menu_items(item_name, price, category_id, description, image, discount, is_active)
                VALUES ('$name', $price, $category, '$desc', '$imagePath', $discount, 1)";
        mysqli_query($conn, $sql);
        $msg = "Added Successfully!";
    } else {
        // EDIT
        if ($imagePath != "") {
            $sql = "UPDATE menu_items SET 
                    item_name='$name', price=$price, category_id=$category, 
                    description='$desc', image='$imagePath', discount=$discount 
                    WHERE id=$id";
        } else {
            $sql = "UPDATE menu_items SET 
                    item_name='$name', price=$price, category_id=$category, 
                    description='$desc', discount=$discount
                    WHERE id=$id";
        }
        mysqli_query($conn, $sql);
        $msg = "Updated Successfully!";
    }
}

// ---------------------------------------------------------
// EDIT MODE
// ---------------------------------------------------------
$editData = [
    "id" => 0,
    "item_name" => "",
    "price" => "",
    "category_id" => "",
    "description" => "",
    "image" => "",
    "discount" => 0
];

if (isset($_GET['edit'])) {
    $eid = intval($_GET['edit']);
    $q = mysqli_query($conn, "SELECT * FROM menu_items WHERE id=$eid");
    $editData = mysqli_fetch_assoc($q);
}

// ---------------------------------------------------------
// LOAD CATEGORIES
// ---------------------------------------------------------
$cats = mysqli_query($conn, "SELECT * FROM menu_categories ORDER BY category_name");

include "admin_header.php";
?>

<style>
body { background: #C39F97; font-family: 'Poppins', sans-serif; }
.container { max-width: 1100px; margin-left: 250px; padding: 30px 20px; }
h2 { margin-top: 70px; color: #000; margin-left: 100px; }

/* Form Card */
.form-box { background: #744542; padding: 30px; border-radius: 14px; margin-bottom: 25px; margin-left: 100px; box-shadow: 0 5px 15px rgba(0,0,0,0.12); color: white; }
.form-box input, .form-box select, .form-box textarea { width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #d7b7b0; margin-bottom: 10px; }
button { padding: 10px 18px; border: none; background: #744542; color: white; border-radius: 8px; cursor: pointer; font-weight: 600; }
button:hover { background: #5d382f; }

/* TABLE */
.table-box { overflow-x: auto; margin-left: 100px; }
table { width: 100%; border-collapse: collapse; background: #fffdfb; border-radius: 12px; }
table th, table td { padding: 12px; border-bottom: 1px solid #f0d8cc; text-align:center; }
table th { background: #744542; color: white; font-weight:600; }
.item-img { width: 65px; height: 55px; border-radius: 6px; object-fit: cover; }
</style>

<div class="container">

<h2>Menu Management</h2>

<?php if ($msg) : ?>
<p style="background:#f6fff1;padding:10px;border-left:4px solid green;color:#3c6c3c;"><?= $msg ?></p>
<?php endif; ?>

<!-- ADD / EDIT FORM -->
<div class="form-box">
<h3><?= $editData['id'] ? "Edit Menu Item" : "Add Menu Item" ?></h3>

<form method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $editData['id'] ?>">

    <label>Item Name</label>
    <input type="text" name="name" required value="<?= htmlspecialchars($editData['item_name']) ?>">

    <label>Price (LKR)</label>
    <input type="number" name="price" step="0.01" required value="<?= $editData['price'] ?>">

    <label>Discount (%)</label>
    <input type="number" name="discount" step="0.01" min="0" max="100" value="<?= $editData['discount'] ?>">

    <label>Category</label>
    <select name="category" required>
        <option value="">Select Category</option>
        <?php
        mysqli_data_seek($cats, 0);
        while ($c = mysqli_fetch_assoc($cats)) : ?>
            <option value="<?= $c['id'] ?>" <?= $c['id'] == $editData['category_id'] ? "selected" : "" ?>>
                <?= $c['category_name'] ?>
            </option>
        <?php endwhile; ?>
    </select>

    <label>Description</label>
    <textarea name="description" rows="3"><?= htmlspecialchars($editData['description']) ?></textarea>

    <label>Image</label>
    <input type="file" name="image">
    <?php if ($editData['image']) : ?>
        <img src="<?= $editData['image'] ?>" width="80" style="margin-top:10px;border-radius:6px;">
    <?php endif; ?>

    <br><br>
    <button type="submit" name="save">
        <?= $editData['id'] ? "Update Item" : "Add Item" ?>
    </button>
</form>
</div>

<!-- TABLE LIST -->
<div class="table-box">
<table>
<tr>
    <th>Image</th>
    <th>Name</th>
    <th>Price</th>
    <th>Discount (%)</th>
    <th>Category</th>
    <th>Description</th>
    <th>Edit</th>
    <th>Delete</th>
</tr>

<?php
$items = mysqli_query($conn, "
    SELECT m.*, c.category_name 
    FROM menu_items m 
    LEFT JOIN menu_categories c ON m.category_id=c.id
    ORDER BY m.id DESC
");

while ($row = mysqli_fetch_assoc($items)) :
?>
<tr>
    <td>
        <?php if ($row['image']) : ?>
            <img src="<?= $row['image'] ?>" class="item-img">
        <?php else : ?>
            No Image
        <?php endif; ?>
    </td>
    <td><?= htmlspecialchars($row['item_name']) ?></td>
    <td>LKR <?= number_format($row['price'], 2) ?></td>
    <td><?= $row['discount'] ?>%</td>
    <td><?= $row['category_name'] ?></td>
    <td><?= htmlspecialchars($row['description']) ?></td>
    <td><a href="menu_management.php?edit=<?= $row['id'] ?>" style="color:green;font-weight:600">Edit</a></td>
    <td><a href="menu_management.php?delete=<?= $row['id'] ?>" onclick="return confirm('Delete item?')" style="color:red;font-weight:600">Delete</a></td>
</tr>
<?php endwhile; ?>
</table>
</div>

</div>
