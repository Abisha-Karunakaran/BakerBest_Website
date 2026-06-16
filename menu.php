<?php
session_start();
include 'header.php'; // Keeps your site navigation intact

// DB connection
$conn = mysqli_connect("localhost","root","root","baker_best");
if(!$conn) die("DB Connection Failed: ".mysqli_connect_error());

// Fetch all active menu items with category
$items = mysqli_query($conn, "
    SELECT m.*, c.category_name 
    FROM menu_items m 
    LEFT JOIN menu_categories c ON m.category_id=c.id
    WHERE m.is_active=1
    ORDER BY c.id, m.item_name
");
?>

<div class="menu-page">
    <h2 class="page-title">Our Delicious Menu</h2>

    <!-- Search Bar -->
    <div class="search-container">
        <input type="text" id="menuSearch" placeholder="Search menu items...">
    </div>

    <div class="menu-grid">
        <?php while($row = mysqli_fetch_assoc($items)): ?>
        <?php
            $originalPrice = $row['price'];
            $discount = $row['discount'];
            $finalPrice = $originalPrice - ($originalPrice * $discount / 100);
        ?>
        <div class="menu-card" 
             data-id="<?=$row['id']?>" 
             data-name="<?=htmlspecialchars($row['item_name'])?>" 
             data-price="<?=$originalPrice?>" 
             data-discount="<?=$discount?>" 
             data-desc="<?=htmlspecialchars($row['description'])?>" 
             data-img="<?=htmlspecialchars($row['image'])?>">

            <div class="card-img-container">
              <img src="<?= htmlspecialchars('Backend/' . $row['image']) ?>" 
                   alt="<?= htmlspecialchars($row['item_name']) ?>" 
                   onclick="showPopup(this)">
            </div>

            <div class="card-content">
                <div class="item-name"><?=htmlspecialchars($row['item_name'])?></div>
                <div class="category-name"><?=htmlspecialchars($row['category_name'])?></div>
                <div class="price">
                    LKR <?= number_format($finalPrice,2) ?>
                    <?php if($discount>0): ?>
                        <span class="original-price">LKR <?= number_format($originalPrice,2) ?></span>
                        <span class="discount-label">-<?= $discount ?>%</span>
                    <?php endif; ?>
                </div>
                <div class="cart-controls">
                    <input type="number" class="qty-box" id="qty_<?=$row['id']?>" value="1" min="1">
                    <button class="btn" 
                        onclick="addToCart(<?=$row['id']?>,'<?=htmlspecialchars($row['item_name'])?>',
                        <?=$originalPrice?>, <?=$discount?>)">
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Popup Overlay -->
<div id="popupOverlay">
    <div id="popupContent">
        <img id="popupImg" src="" alt="">
        <h3 id="popupTitle"></h3>
        <p id="popupDesc"></p>
        <p id="popupPrice"></p>
        <button class="btn close-popup-btn" onclick="closePopup()">Close</button>
    </div>
</div>

<form id="cartForm" method="POST" action="order.php">
    <input type="hidden" name="cart_json" id="cart_json">
</form>
<div class="cart-btn" onclick="goToCart()">🛒 View Cart</div>

<style>
  body {
    background: url("assets/back.png");}
/* Page Title */
.page-title { text-align: center; font-size: 36px; margin: 50px 0 30px; color: #934C47; font-weight: 700; }

/* Search Bar */
.search-container { text-align:center; margin-bottom:30px; }
.search-container input { width:300px; padding:8px 12px; border-radius:8px; border:1px solid #c9b1b1; font-size:16px; transition: all 0.3s ease; }
.search-container input:focus { border-color: #934C47; outline: none; box-shadow: 0 0 8px rgba(147, 76, 71, 0.4); }

/* Menu Grid */
.menu-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px; padding: 0 20px 50px 20px; }

/* Menu Card */
.menu-card { background: #fffaf6; border-radius: 18px; border: 3px solid #bbb9b9ff; box-shadow: 0 6px 15px rgba(0,0,0,0.08); overflow: hidden; transition: transform 0.3s ease, box-shadow 0.3s ease; }
.menu-card:hover { transform: translateY(-8px); box-shadow: 0 15px 25px rgba(0,0,0,0.18); }

/* Image Container */
.card-img-container { width: 100%; height: 280px; overflow: hidden; border-radius: 12px; background: #f5f0ec; }
.card-img-container img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
.menu-card:hover .card-img-container img { transform: scale(1.1) rotate(1deg); }

/* Card Content */
.card-content { padding: 20px; text-align: center; }
.item-name { font-size: 20px; font-weight: 700; color: #744542; margin-bottom: 5px; }
.category-name { font-size: 14px; font-weight: 500; color: #b48b81; margin-bottom: 8px; }
.price { font-size: 18px; font-weight: 600; color: #934C47; margin-bottom: 15px; }
.original-price { text-decoration: line-through; font-size: 14px; color: #b48b81; margin-left: 5px; }
.discount-label { color:red; font-size: 14px; margin-left:5px; font-weight:600; }

.cart-controls { display:flex; justify-content:center; align-items:center; gap:10px; }
.qty-box { width:60px; padding:6px; border-radius:6px; border:1px solid #c9b1b1; text-align:center; }
.btn { padding:8px 14px; background:#934C47; color:#fff; border:none; border-radius:8px; font-weight:600; cursor:pointer; transition: transform 0.2s ease, background 0.3s ease; }
.btn:hover { background:#5b352f; transform:scale(1.05); }

.cart-btn {
    position: fixed;
    top: 25px;
    right: 25px;
    background: #934C47;
    color: white;
    padding: 14px 20px;
    border-radius: 50px;
    font-size: 18px;
    cursor: pointer;
    box-shadow: 0 6px 15px rgba(0,0,0,0.25);
    transition: transform 0.3s ease, background 0.3s ease;
    z-index: 1000;
    margin-top:70px; /* Remove bottom margin */
}.cart-btn:hover { background:#5b352f; transform:scale(1.1); }

/* Popup */
#popupOverlay { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:1000; animation: fadeIn 0.3s ease; }
#popupContent { background:#fffaf6; padding:25px; border-radius:16px; width:420px; max-width:90%; position:absolute; top:50%; left:50%; transform: translate(-50%, -50%) scale(0); animation: scaleIn 0.3s forwards; text-align:center; box-shadow: 0 10px 25px rgba(0,0,0,0.2); }
#popupContent img { width:100%; height:220px; object-fit:cover; border-radius:12px; margin-bottom:15px; }
.closeBtn, .close-popup-btn { padding:6px 12px; background:#934C47; color:white; border:none; border-radius:6px; cursor:pointer; }

@keyframes scaleIn { from { transform: translate(-50%, -50%) scale(0.6); opacity:0; } to { transform: translate(-50%, -50%) scale(1); opacity:1; } }
@keyframes fadeIn { from { opacity:0; } to { opacity:1; } }

@media(max-width:820px){ .menu-grid{grid-template-columns:repeat(auto-fill,minmax(200px,1fr));} .card-img-container{height:150px;} }
</style>

<script>
let cart = JSON.parse(localStorage.getItem("cart") || "[]");

function addToCart(id,name,price,discount){
    let qty = parseInt(document.getElementById("qty_"+id).value);
    if(isNaN(qty) || qty<1) qty=1;
    let finalPrice = price - (price * discount / 100);
    let exist = cart.find(item => item.id === id);
    if(exist) exist.qty += qty;
    else cart.push({id:id,name:name,price:finalPrice,qty:qty,discount:discount});
    localStorage.setItem("cart", JSON.stringify(cart));
    alert(name + " added to cart!");
}

function goToCart(){
    if(cart.length===0){ alert("Cart empty!"); return; }
    document.getElementById("cart_json").value = JSON.stringify(cart);
    document.getElementById("cartForm").submit();
}

function showPopup(img){
    const card = img.closest('.menu-card');
    let imgPath = 'Backend/' + card.getAttribute('data-img');
    document.getElementById('popupImg').src = imgPath;
    document.getElementById('popupTitle').innerText = card.getAttribute('data-name');
    document.getElementById('popupDesc').innerText = card.getAttribute('data-desc');

    let price = parseFloat(card.getAttribute('data-price'));
    let discount = parseFloat(card.getAttribute('data-discount'));
    let finalPrice = price - (price * discount / 100);
    document.getElementById('popupPrice').innerText = "Price: LKR " + finalPrice.toFixed(2);

    document.getElementById('popupOverlay').style.display = 'block';
    document.getElementById('popupContent').style.animation = 'scaleIn 0.3s forwards';
}

function closePopup(){
    document.getElementById('popupOverlay').style.display = 'none';
}

// Menu Search Filter
document.getElementById('menuSearch').addEventListener('input', function() {
    const query = this.value.toLowerCase();
    const cards = document.querySelectorAll('.menu-card');
    cards.forEach(card => {
        const name = card.getAttribute('data-name').toLowerCase();
        const category = card.querySelector('.category-name').innerText.toLowerCase();
        card.style.display = (name.includes(query) || category.includes(query)) ? 'block' : 'none';
    });
});
</script>

<?php include 'footer.php'; ?>
