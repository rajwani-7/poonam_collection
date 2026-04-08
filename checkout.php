<?php
require_once 'config/config.php';

if (!isset($_SESSION['customer_logged_in']) || $_SESSION['customer_logged_in'] !== true) {
    header('Location: login.php?redirect=checkout.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Poonam Collection</title>
    <link rel="stylesheet" href="assets/css/auth.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="checkout-wrap">
        <div class="brand" style="margin-bottom: 18px;">
            <img src="assets/images/logo.png?v=20260316" alt="Poonam Collection logo">
            <div>
                <h1>Poonam Collection</h1>
                <p>Secure Checkout</p>
            </div>
        </div>

        <div class="checkout-grid">
            <section class="panel">
                <h2>Shipping Details</h2>
                <div class="alert error" id="errorBox"></div>
                <div class="alert success" id="successBox"></div>

                <form id="checkoutForm">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" value="<?php echo htmlspecialchars($_SESSION['customer_name']); ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" value="<?php echo htmlspecialchars($_SESSION['customer_email']); ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="tel" id="phone" name="phone" required>
                    </div>

                    <div class="form-group">
                        <label for="shippingAddress">Shipping Address</label>
                        <textarea id="shippingAddress" name="shippingAddress" required></textarea>
                    </div>

                    <button type="submit" id="placeOrderBtn">Place Order</button>
                </form>

                <div class="checkout-actions">
                    <a href="index.html">Continue Shopping</a>
                    <a href="logout.php">Logout Account</a>
                </div>
            </section>

            <aside class="panel">
                <h2>Your Order</h2>
                <div id="emptyCartMessage" class="auth-subtitle" style="display:none;">Your cart is empty. Add products before checkout.</div>
                <div class="order-list" id="orderList"></div>
                <div class="order-total">
                    <span>Total</span>
                    <span id="orderTotal">Rs 0</span>
                </div>
            </aside>
        </div>
    </div>

    <script>
        const orderList = document.getElementById('orderList');
        const orderTotal = document.getElementById('orderTotal');
        const emptyCartMessage = document.getElementById('emptyCartMessage');
        const checkoutForm = document.getElementById('checkoutForm');
        const errorBox = document.getElementById('errorBox');
        const successBox = document.getElementById('successBox');
        const placeOrderBtn = document.getElementById('placeOrderBtn');

        const cart = JSON.parse(sessionStorage.getItem('checkout_cart') || '[]');

        function formatCurrency(value) {
            return 'Rs ' + Number(value || 0).toLocaleString('en-IN');
        }

        function renderOrderSummary() {
            if (!Array.isArray(cart) || cart.length === 0) {
                emptyCartMessage.style.display = 'block';
                checkoutForm.style.display = 'none';
                return;
            }

            let total = 0;
            orderList.innerHTML = cart.map((item) => {
                const qty = Number(item.quantity || 1);
                const price = Number(item.price || 0);
                const itemTotal = qty * price;
                total += itemTotal;

                return `
                    <div class="order-item">
                        <span>${item.name} x ${qty}</span>
                        <strong>${formatCurrency(itemTotal)}</strong>
                    </div>
                `;
            }).join('');

            orderTotal.textContent = formatCurrency(total);
        }

        checkoutForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            errorBox.style.display = 'none';
            successBox.style.display = 'none';

            const phone = document.getElementById('phone').value.trim();
            const shippingAddress = document.getElementById('shippingAddress').value.trim();

            if (cart.length === 0) {
                errorBox.textContent = 'Cart is empty.';
                errorBox.style.display = 'block';
                return;
            }

            placeOrderBtn.disabled = true;
            placeOrderBtn.textContent = 'Placing Order...';

            try {
                const response = await fetch('api/orders.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        phone,
                        shipping_address: shippingAddress,
                        items: cart
                    })
                });

                const result = await response.json();

                if (!result.success) {
                    throw new Error(result.message || 'Failed to place order');
                }

                sessionStorage.removeItem('checkout_cart');
                successBox.textContent = `Order placed successfully. Order ID: #${result.order_id}`;
                successBox.style.display = 'block';
                checkoutForm.reset();
                setTimeout(() => {
                    window.location.href = 'index.html';
                }, 2000);
            } catch (error) {
                errorBox.textContent = error.message;
                errorBox.style.display = 'block';
            } finally {
                placeOrderBtn.disabled = false;
                placeOrderBtn.textContent = 'Place Order';
            }
        });

        renderOrderSummary();
    </script>
</body>
</html>
