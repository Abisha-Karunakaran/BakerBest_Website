<?php 
include 'header.php'; 


$user_id = $_SESSION['user_id'] ?? null;
$customer_name = $_SESSION['user_name'] ?? "Guest";
$customer_email = $_SESSION['user_email'] ?? "guest@example.com";
?>

<div class="container order-page">
    <h2>Your Order</h2>
    <div id="cartContainer"></div>
    <div class="total" id="totalPrice"></div>
    <button class="pay-btn" id="checkoutBtn">Checkout</button>

    <hr style="margin:30px 0;">

    <h2>Order History</h2>
    <button class="btn" id="viewOrderHistory">View Past Orders</button>
    <div id="orderHistoryContainer" style="margin-top:20px;"></div>
</div>

<!-- PICKUP POPUP -->
<div id="pickupOverlay" class="popupOverlay">
    <div class="popupContent">
        <h3>Select Pickup Details</h3>
        <label>Pickup Date:</label>
        <input type="date" id="pickupDate">
        <label>Pickup Time:</label>
        <input type="time" id="pickupTime">
        <button class="btn" id="continueToPayment">Continue</button>
        <button class="btn" onclick="closePickup()">Cancel</button>
    </div>
</div>

<!-- PAYMENT POPUP -->
<div id="paymentOverlay" class="popupOverlay">
    <div class="popupContent">
        <h3>Select Payment Method</h3>
    
        <label><input type="radio" name="payment" value="pickup"> Pay on Pickup</label><br><br>
        <button class="btn" id="confirmPayment">Confirm</button>
        <button class="btn" onclick="closePayment()">Cancel</button>
    </div>
</div>

<!-- SUCCESS POPUP -->
<div id="successOverlay" class="popupOverlay">
    <div class="popupContent">
        <h3>Order Placed Successfully 🎉</h3>
        <p>Pickup Date: <b id="successDate"></b></p>
        <p>Pickup Time: <b id="successTime"></b></p>
        <p>Payment Method: <b id="successMethod"></b></p>
        <button class="btn" onclick="window.location.href='menu.php'">Continue Shopping</button>
    </div>
</div>

<style>
  body {
    background: url("assets/back.png");
}

.order-page { 
    max-width:1200px; margin:30px auto; padding:20px; background:#fff; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.1);
}
.order-page h2 { text-align:center;color:#744542;margin-bottom:20px; }
.order-page button{ padding:8px 12px; background:#744542; color:white; border:none; border-radius:6px; cursor:pointer; margin:2px; }
.order-page button:hover{ background:#5b352f; }
.order-page .total{ font-weight:700; text-align:right; margin-top:10px; font-size:18px; }
.order-page .pay-btn{ display:block; width:100%; text-align:center; padding:12px; background:#28a745; color:white; font-size:18px; border-radius:8px; cursor:pointer; border:none; margin-top:10px; }
.order-page .pay-btn:hover{ background:#218838; }

.popupOverlay{ display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000; }
.popupContent{ background:white; padding:20px; border-radius:12px; width:300px; max-width:90%; position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); text-align:center; }
.popupContent input{ width:90%; padding:8px; margin:8px 0; }
#orderHistoryContainer table{ width:100%; border-collapse:collapse; margin-top:15px; }
#orderHistoryContainer th, #orderHistoryContainer td{ padding:10px; border:1px solid #ddd; text-align:center; }
#orderHistoryContainer th{ background:#744542; color:white; }
</style>

<script>
// Load cart from localStorage
let cart = JSON.parse(localStorage.getItem('cart') || '[]');

function renderCart(){
    const container = document.getElementById('cartContainer');
    if(cart.length===0){
        container.innerHTML = '<p>Your cart is empty!</p>';
        document.getElementById('totalPrice').innerText='';
        document.getElementById('checkoutBtn').style.display='none';
        return;
    }
    let html = '<table width="100%"><tr><th align="left">Item</th><th align="left">Price</th><th align="left">Quantity</th><th align="left">Subtotal</th><th align="left">Remove</th></tr>';
    let total = 0;
    cart.forEach((item,index)=>{
        let subtotal = item.price * item.qty;
        total += subtotal;
        html += `<tr data-index="${index}">
            <td>${item.name}</td>
            <td>LKR ${item.price.toFixed(2)}</td>
            <td>${item.qty}</td>
            <td>LKR ${subtotal.toFixed(2)}</td>
            <td><button class="removeBtn" data-index="${index}">Remove</button></td>
        </tr>`;
    });
    html += '</table>';
    container.innerHTML = html;
    document.getElementById('totalPrice').innerText = 'Total: LKR '+total.toFixed(2);
    document.getElementById('checkoutBtn').style.display='block';

    document.querySelectorAll('.removeBtn').forEach(btn=>{
        btn.addEventListener('click', function(){
            const idx = parseInt(btn.getAttribute('data-index'));
            if(!isNaN(idx)){
                cart.splice(idx,1);
                localStorage.setItem('cart',JSON.stringify(cart));
                renderCart();
            }
        });
    });
}

renderCart();

// Checkout button - must be logged in
document.getElementById('checkoutBtn').onclick = () => { 
    <?php if(!$user_id): ?>
        alert("You must be logged in to place an order!");
        window.location.href = 'login.php';
    <?php else: ?>
        document.getElementById('pickupOverlay').style.display = 'block'; 
    <?php endif; ?>
};

function closePickup(){ 
    document.getElementById('pickupOverlay').style.display = 'none'; 
}

document.getElementById('continueToPayment').onclick = () => {
    let date = document.getElementById('pickupDate').value;
    let time = document.getElementById('pickupTime').value;

    if (!date || !time) { 
        alert("Please select pickup date and time."); 
        return; 
    }

    let now = new Date();
    let selectedDateTime = new Date(date + "T" + time);

    if (selectedDateTime < now) {
        alert("Pickup date and time cannot be in the past!");
        return;
    }

    closePickup();
    document.getElementById('paymentOverlay').style.display = 'block';
};

function closePayment(){ document.getElementById('paymentOverlay').style.display='none'; }

document.getElementById('confirmPayment').onclick = () => {
    let method = document.querySelector("input[name='payment']:checked");
    if(!method){ alert("Please choose a payment method."); return; }

    let pickupDate = document.getElementById('pickupDate').value;
    let pickupTime = document.getElementById('pickupTime').value;

    // AJAX save order
    fetch('Backend/save_order.php', {
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify({
            cart: cart,
            pickup_date: pickupDate,
            pickup_time: pickupTime,
            payment_method: method.value,
            customer_name: "<?= $customer_name ?>",
            customer_email: "<?= $customer_email ?>",
        })
    })
    .then(res=>res.json())
    .then(data=>{
        if(data.status==='success'){
            document.getElementById('paymentOverlay').style.display='none';
            document.getElementById('successDate').innerText=pickupDate;
            document.getElementById('successTime').innerText=pickupTime;
            document.getElementById('successMethod').innerText = method.value === 'pickup' ? 'Pay on Pickup' : 'Bank';

            localStorage.removeItem('cart');
            cart = [];
            renderCart();

            document.getElementById('successOverlay').style.display='block';
        }else{
            alert("Error: "+data.msg);
        }
    })
    .catch(err=>{
        console.error(err);
        alert("Server error while placing order.");
    });
};

// Order history
document.getElementById('viewOrderHistory').addEventListener('click', function(){
    fetch('view_order_history.php')
    .then(res=>res.text())
    .then(data=>document.getElementById('orderHistoryContainer').innerHTML=data);
});
</script>

<?php include 'footer.php'; ?>  
