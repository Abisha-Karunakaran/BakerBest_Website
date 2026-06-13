<?php // footer.php ?>
<style>
footer {
    background: #744542ff;
    color: #ffffffff;
    padding: 60px 8%;
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 40px;
    border-top: 3px solid #744542ff;
}

/* COLUMNS */
.footer-col {
    flex: 1;
    min-width: 250px;
}

.footer-title {
    font-size: 22px;
    margin-bottom: 15px;
    color: #ffffffff;
    font-weight: 600;
}

/* TEXT */
.footer-col p,
.footer-col a {
    font-size: 16px;
    line-height: 26px;
    color: #ffffffff;
    text-decoration: none;
}

/* LINKS */
.footer-links a {
    display: block;
    margin-bottom: 8px;
    transition: .3s;
}

.footer-links a:hover {
    color: #000000ff;
}

/* SERVICES LIST */
.footer-services li {
    list-style: none;
    margin-bottom: 6px;
    font-size: 16px;
}

/* FOLLOW ICONS */
.follow-links a {
    margin-right: 12px;
    font-size: 16px;
    color: #ffffffff;
    text-decoration: none;
}

.follow-links a:hover {
    color: #000000ff;
}

/* NEWSLETTER */
.newsletter input {
    width: 100%;
    padding: 12px;
    border: 1px solid #c7b8ae;
    border-radius: 6px;
    margin-bottom: 12px;
}

.newsletter button {
    width: 100%;
    padding: 12px;
    background: #744542ff;
    color: #fff;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 16px;
}

.newsletter button:hover {
    background: #000000ff;
}

/* BOTTOM */
.footer-bottom {
    width: 100%;
    text-align: center;
    margin-top: 35px;
    padding-top: 25px;
    border-top: 1px solid #c7b8ae;
    font-size: 15px;
    color: #ffffffff;
}
</style>

<footer id="contact">

    <!-- ABOUT SECTION -->
    <div class="footer-col">
        <h3 class="footer-title">🍞 BakerBest - Freshly Baked Every Day</h3>
        <p>Bringing you soft breads, delightful cakes, and handcrafted treats made with love and the finest ingredients.</p>
    </div>

    <!-- QUICK LINKS -->
    <div class="footer-col">
        <h3 class="footer-title">Quick Links</h3>
        <div class="footer-links">
            <a href="index.php">Home</a>
            <a href="about.php">About Us</a>
            <a href="menu.php">Menu</a>
            <a href="order.php">Order Online</a>
            <a href="gallery.php">Gallery</a>
            <a href="contact.php">Contact Us</a>
        </div>
    </div>

    <!-- CONTACT INFO -->
    <div class="footer-col">
        <h3 class="footer-title">Contact Us</h3>
        <p><a href="https://www.google.com/maps/place/Jaffna">No. 25, Bakery Street, Jaffna</a></p>
        <p><a href="tel:+94771234567">+94 771234567</a></p>
        <p>WhatsApp: <a href="https://wa.me/94771234567">+94 771234567</a></p>
        <p><a href="bakerbest@gmail.com">bakerbest@gmail.com</a></p>
        <p>Open Daily: 7:00 AM - 10:00 PM</p>
    </div>



    <!-- SERVICES -->
    <div class="footer-col">
         <h3 class="footer-title" >Follow Us</h3>
        <div class="follow-links">
            <a href="https://www.facebook.com">Facebook</a>
            <a href="https://www.instagram.com">Instagram</a>
            <a href="https://www.whatsapp.com">WhatsApp</a>
        </div>
        <h3 class="footer-title" style="margin-top: 20px;">Services</h3>
        <ul class="footer-services">
            <li>✔ Custom cakes for birthdays & events</li>
            <li>✔ Freshly baked items daily</li>
            <li>✔ Cash on pickup</li>
        </ul>

        
    </div>

    <!-- COPYRIGHT -->
    <div class="footer-bottom">
        © <?php echo date("Y"); ?> BakerBest. All Rights Reserved.
    </div>

</footer>
