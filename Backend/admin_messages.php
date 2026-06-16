<?php
include 'admin_header.php';
include 'db.php';

// =======================
// DELETE MESSAGE
// =======================
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $del_query = "DELETE FROM contact_messages WHERE id = $delete_id";
    mysqli_query($conn, $del_query);
    echo "<script>alert('Message Deleted Successfully!'); window.location='admin_messages.php';</script>";
    exit();
}

// =======================
// SAVE ADMIN REPLY
// =======================
if (isset($_POST['reply_btn'])) {
    $id = intval($_POST['msg_id']);
    $reply = mysqli_real_escape_string($conn, $_POST['reply']);

    $query = "UPDATE contact_messages 
              SET admin_reply = '$reply', reply_at = NOW()
              WHERE id = $id";

    mysqli_query($conn, $query);

    echo "<script>alert('Reply Sent Successfully!'); window.location='admin_messages.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Messages</title>

<style>
:root{
    --bg: #C39F97;
    --text: #744542;
    --white: #ffffff;
    --card-radius: 14px;
    --shadow: 0 4px 12px rgba(0,0,0,0.15);
    --light-bg: #f3e7e5;
}

.page-wrapper {
    margin-left: 270px;
    padding: 120px 30px;
    width: 100%;
}
@media (max-width:900px) {
    .page-wrapper { margin-left: 0; padding: 120px 15px; }
}

.table-card {
    background: var(--white);
    padding: 25px;
    border-radius: var(--card-radius);
    box-shadow: var(--shadow);
    width: 100%;
    overflow-x: auto;
}

h2 {
    color: var(--text);
    margin-bottom: 20px;
    font-size: 26px;
    text-align: center;
}

table {
    width: 100%;
    font-size: 14px;
}

table th {
    background: var(--text);
    color: var(--white);
    padding: 12px;
    border-radius: 8px 8px 0 0;
    font-weight: 600;
}

table td {
    padding: 12px;
    border-bottom: 1px solid #ddd;
    vertical-align: top;
}

.reply-box {
    width: 100%;
    padding: 10px;
    border-radius: 8px;
    border: 1px solid var(--text);
    resize: none;
    font-size: 13px;
}

.reply-btn, .delete-btn {
    margin-top: 8px;
    padding: 8px 15px;
    background: var(--text);
    color: var(--white);
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 13px;
    display: inline-block;
    text-decoration: none;
}

.reply-btn:hover, .delete-btn:hover { background: #5d362f; }

.no-reply { color: #b33a3a; font-weight: 600; }
.replied { color: #1c6e2e; font-weight: 600; }

@media(max-width:600px){
    table td, table th { font-size: 12px; padding: 8px; }
}
</style>
</head>
<body>

<div class="page-wrapper">
    <div class="table-card">
        <h2>Customer Messages</h2>

        <table>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Email</th>
                <th>Message</th>
                <th>Admin Reply</th>
                <th>Action</th>
            </tr>

            <?php
            $result = mysqli_query($conn, "SELECT * FROM contact_messages ORDER BY id DESC");

            while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <tr>
                <td><?= $row['id']; ?></td>

                <td>
                    <strong><?= $row['user_name']; ?></strong><br>
                    <small><?= $row['created_at']; ?></small>
                </td>

                <td><?= $row['user_email']; ?></td>

                <td><?= nl2br($row['user_message']); ?></td>

                <td>
                    <?php if (empty($row['admin_reply'])) { ?>
                        <span class="no-reply">No reply yet</span>
                    <?php } else { ?>
                        <span class="replied"><?= nl2br($row['admin_reply']); ?></span>
                        <br><small><i>Replied: <?= $row['reply_at']; ?></i></small>
                    <?php } ?>
                </td>

                <td style="width:300px;">
                    <form method="POST" style="margin-bottom:5px;">
                        <textarea name="reply" class="reply-box" placeholder="Type reply..."><?= $row['admin_reply']; ?></textarea>
                        <input type="hidden" name="msg_id" value="<?= $row['id']; ?>">
                        <button type="submit" name="reply_btn" class="reply-btn">Send Reply</button>
                    </form>

                    <a href="admin_messages.php?delete_id=<?= $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this message?');" class="delete-btn">Delete</a>
                </td>
            </tr>
            <?php } ?>

        </table>
    </div>
</div>

</body>
</html>
