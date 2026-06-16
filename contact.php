<?php 
include 'header.php'; 
include 'Backend/db.php'; // DB connection

$message_sent = false;

// ==== FORM SUBMIT HANDLING ====
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    $query = "INSERT INTO contact_messages (user_name, user_email, user_message)
              VALUES ('$name', '$email', '$message')";

    if (mysqli_query($conn, $query)) {
        $message_sent = true;
    }
}

// Fetch messages and replies for logged-in customer
$user_email = $_SESSION['user_email'] ?? '';
$messages = [];
if (!empty($user_email)) {
    $stmt = $conn->prepare("SELECT * FROM contact_messages WHERE user_email = ? ORDER BY created_at DESC");
    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact</title>

<style>
/* ===== CONTACT PAGE STYLE ===== */
.contact-section { padding: 120px 10% 60px; background: url("assets/back.png"); color:  #744542ff; }
.contact-title { text-align: center; font-size: 45px; font-family: 'Great Vibes', cursive; margin-bottom: 10px; }
.contact-sub { text-align: center; font-size: 17px; margin-bottom: 40px; color:  #744542ff; }

.contact-container { display: flex; flex-wrap: wrap; gap: 40px; justify-content: center; }

.contact-info, .contact-form { background:  #dcd6d5ff; padding: 25px; border-radius: 20px; box-shadow: 0 5px 16px rgba(0,0,0,0.12); }

.contact-info h3 { font-size: 26px; margin-bottom: 15px; color: #744542ff; }
.info-item p { font-size: 16px; line-height: 1.6; margin-bottom: 15px; }

.map-box { width: 100%; height: 250px; border-radius: 15px; overflow: hidden; margin-top: 15px; }

.contact-form label { font-weight: 600; margin: 12px 0 6px; display: block; }
.contact-form input, .contact-form textarea { width: 100%; padding: 12px; border: 1px solid #d6b195; border-radius: 10px; background: #fff; font-size: 15px; }
.contact-form textarea { height: 130px; resize: none; }

.contact-btn { margin-top: 20px; width: 100%; padding: 14px; background: #744542ff; color: #fff; font-size: 18px; border-radius: 10px; cursor: pointer; border: none; font-weight: 600; }
.contact-btn:hover { background: #5d362fff; }

.success-msg { text-align: center; padding: 12px; background: #c8e6c9; color: #256029; border-radius: 8px; margin-bottom: 15px; font-weight: bold; }

/* REPLY TABLE */
.reply-table { width: 100%; border-collapse: collapse; margin-top: 30px; background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
.reply-table th, .reply-table td { padding: 10px 12px; border: 1px solid #ddd; text-align: left; font-size: 14px; }
.reply-table th { background: #744542; color: #fff; font-weight: 600; }
.reply-table tr:nth-child(even) { background: #f9f9f9; }

@media(max-width: 900px) {
    .contact-container { display: block; }
    .contact-info, .contact-form { margin-bottom: 30px; }
}
</style>
</head>
<body>

<section class="contact-section">
    <h2 class="contact-title">Contact Us</h2>
    <p class="contact-sub">We’d love to hear from you! Send us a message or visit our bakery.</p>

    <div class="contact-container">

        <!-- INFO + MAP -->
        <div class="contact-info">
            <h3>Get in Touch</h3>
            <div class="info-item"><p><strong>Address:</strong><br>No. 25, Bakery Street, Jaffna</p></div>
            <div class="info-item"><p><strong>Phone:</strong><br>+94 77 123 4567</p></div>
            <div class="info-item"><p><strong>Email:</strong><br>bakerbest@gmail.com</p></div>
            <div class="info-item"><p><strong>Opening Hours:</strong><br>Open Daily: 7:00 AM - 10:00 PM</p></div>

            <div class="map-box">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.127374242422!2d80.00007397548002!3d9.66147249278445!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3afe540b8d2c6119%3A0xdcc7146313c2e2d0!2sJaffna%20Town!5e0!3m2!1sen!2slk!4v1700000000000" 
                    width="100%" 
                    height="100%" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>

        <!-- FORM -->
        <form class="contact-form" method="POST">
            <?php if ($message_sent): ?>
                <div class="success-msg">Your message has been sent successfully!</div>
            <?php endif; ?>

            <h3>Send a Message</h3>

            <label>Your Name</label>
            <input type="text" name="name" 
                   value="<?= !$message_sent ? ($_SESSION['user_name'] ?? '') : '' ?>" 
                   required placeholder="Enter your name">

            <label>Email</label>
            <input type="email" name="email" 
                   value="<?= !$message_sent ? ($_SESSION['user_email'] ?? '') : '' ?>" 
                   required placeholder="Enter your email">

            <label>Message</label>
            <textarea name="message" required placeholder="Write your message..."></textarea>

            <button type="submit" class="contact-btn">Send Message</button>
        </form>

    </div>

    <!-- REPLY TABLE BELOW CONTACT SECTION -->
    <div style="max-width:1100px; margin:30px auto;">
        <h3 style="color:#744542;">Your Messages and Admin Replies</h3>

        <?php if(empty($messages)): ?>
            <p>No messages yet.</p>
        <?php else: ?>
            <table class="reply-table">
                <thead>
                    <tr>
                        <th>Your Message</th>
                        <th>Sent At</th>
                        <th>Admin Reply</th>
                        <th>Replied At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($messages as $msg): ?>
                        <tr>
                            <td><?= nl2br(htmlspecialchars($msg['user_message'])) ?></td>
                            <td><?= $msg['created_at'] ?></td>
                            <td>
                                <?php if(!empty($msg['admin_reply'])): ?>
                                    <?= nl2br(htmlspecialchars($msg['admin_reply'])) ?>
                                <?php else: ?>
                                    <span style="color:#b33a3a;">No reply yet</span>
                                <?php endif; ?>
                            </td>
                            <td><?= !empty($msg['reply_at']) ? $msg['reply_at'] : '-' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

</section>

<?php include 'footer.php'; ?>
</body>
</html>
