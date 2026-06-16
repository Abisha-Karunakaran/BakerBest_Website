<?php
include 'admin_header.php';
include 'db.php';

// Upload multiple images
if (isset($_POST['upload_btn'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);

    $target_dir = "assets/gallery/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    // Loop through multiple uploaded files
    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
        $file_name = basename($_FILES['images']['name'][$key]);
        $target_file = $target_dir . time() . "_" . $file_name;

        if (move_uploaded_file($_FILES['images']['tmp_name'][$key], $target_file)) {
            mysqli_query($conn, "INSERT INTO gallery_images (image_path, title) VALUES ('$target_file', '$title')");
        }
    }

    echo "<script>alert('Images Uploaded Successfully'); window.location='gallery_management.php';</script>";
    exit();
}

// Delete image
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $img = mysqli_fetch_assoc(mysqli_query($conn, "SELECT image_path FROM gallery_images WHERE id=$id"));
    if ($img && file_exists($img['image_path'])) unlink($img['image_path']);
    mysqli_query($conn, "DELETE FROM gallery_images WHERE id=$id");
    echo "<script>alert('Image Deleted'); window.location='gallery_management.php';</script>";
    exit();
}

$images = mysqli_query($conn, "SELECT * FROM gallery_images ORDER BY created_at DESC");
?>

<div class="page-wrapper">
    <h2>Gallery Management</h2>

    <form method="POST" enctype="multipart/form-data" style="margin-bottom:30px;">
        <input type="text" name="title" placeholder="Image Title" required>
        <input type="file" name="images[]" accept="image/*" multiple required>
        <button type="submit" name="upload_btn">Upload Images</button>
    </form>

    <table border="1" cellpadding="10" style="width:100%; border-collapse:collapse;">
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Title</th>
            <th>Uploaded At</th>
            <th>Action</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($images)): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><img src="<?= $row['image_path'] ?>" width="100"></td>
                <td><?= $row['title'] ?></td>
                <td><?= $row['created_at'] ?></td>
                <td>
                    <a href="gallery_management.php?delete_id=<?= $row['id'] ?>" onclick="return confirm('Delete this image?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

<style>
.page-wrapper {
    margin-left: 270px;
    padding: 120px 30px;
    width: 100%;
    font-family: 'Poppins', sans-serif;
    background: #f3e7e5;
}
.page-wrapper h2 {
    color: #744542;
    font-size: 28px;
    margin-bottom: 25px;
    text-align: center;
}
form {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    align-items: center;
    background: #ffffff;
    padding: 20px;
    border-radius: 14px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}
form input[type="text"], 
form input[type="file"] {
    flex: 1;
    padding: 10px 12px;
    border: 1px solid #d6b195;
    border-radius: 8px;
    font-size: 14px;
}
form button {
    padding: 10px 20px;
    background: #744542;
    color: #fff;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 600;
    transition: background 0.3s ease;
}
form button:hover { background: #5d362f; }
table { width: 100%; border-collapse: collapse; font-size: 14px; }
table th { background: #744542; color: #fff; font-weight: 600; padding: 12px; text-align: left; }
table td { padding: 12px; border-bottom: 1px solid #ddd; vertical-align: middle; }
table tr:nth-child(even) { background: #f9f9f9; }
table a { color: #744542; font-weight: 600; text-decoration: none; margin-right: 10px; }
table a:hover { text-decoration: underline; }
.table-card img { border-radius: 8px; max-width: 100px; height: auto; object-fit: cover; }
</style>
