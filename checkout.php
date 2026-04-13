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
        <div class="checkout-topbar">
            <div class="brand" style="margin-bottom: 0;">
                <img src="assets/images/logo.png?v=20260316" alt="Poonam Collection logo">
                <div>
                    <h1>Poonam Collection</h1>
                    <p>Secure Checkout</p>
                </div>
            </div>
            <div class="checkout-top-actions">
                <a href="index.html" class="top-link">Back to Home</a>
                <a href="logout.php" class="top-link logout-btn">Logout</a>
            </div>
        </div>

        <div class="checkout-headline">
            <h2>Complete Your Order</h2>
            <p>Review your products, add shipping details, and place the order securely.</p>
        </div>

        <div class="checkout-grid">
            <section class="panel">
                <h2>Shipping Details</h2>
                <p class="panel-subtitle">Delivery updates will be sent to your registered email and phone.</p>
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

                    <div class="form-group">
                        <label for="deliveryNotes">Delivery Notes (Optional)</label>
                        <textarea id="deliveryNotes" name="deliveryNotes" placeholder="Landmark, preferred delivery time, or any special note"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="paymentMethod">Payment Method</label>
                        <select id="paymentMethod" name="paymentMethod">
                            <option value="cod">Cash on Delivery</option>
                            <option value="upi" disabled>UPI (Coming Soon)</option>
                            <option value="card" disabled>Card (Coming Soon)</option>
                        </select>
                    </div>

                    <button type="submit" id="placeOrderBtn">Place Order</button>
                </form>

                <div class="checkout-actions">
                    <a href="index.html" class="secondary-btn">Continue Shopping</a>
                    <button type="button" id="clearCartBtn" class="secondary-btn">Clear Cart</button>
                </div>
            </section>

            <aside class="panel">
                <h2>Your Order</h2>
                <p class="panel-subtitle">Double-check quantities and totals before placing order.</p>
                <div id="emptyCartMessage" class="auth-subtitle" style="display:none;">Your cart is empty. Add products before checkout.</div>
                <div class="order-list" id="orderList"></div>

                <div class="order-meta">
                    <div class="meta-row">
                        <span>Total Items</span>
                        <strong id="totalItems">0</strong>
                    </div>
                    <div class="meta-row">
                        <span>Subtotal</span>
                        <strong id="orderSubtotal">Rs 0</strong>
                    </div>
                    <div class="meta-row">
                        <span>Shipping</span>
                        <strong id="shippingCharge">Free</strong>
                    </div>
                </div>

                <div class="order-total">
                    <span>Total</span>
                    <span id="orderTotal">Rs 0</span>
                </div>

                <div class="checkout-note">
                    <strong>Secure Checkout</strong>
                    <p>Your order details are safely processed, and confirmation will be shared instantly.</p>
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
        const totalItemsEl = document.getElementById('totalItems');
        const orderSubtotalEl = document.getElementById('orderSubtotal');
        const clearCartBtn = document.getElementById('clearCartBtn');

        const cart = JSON.parse(sessionStorage.getItem('checkout_cart') || '[]');

        function formatCurrency(value) {
            return 'Rs ' + Number(value || 0).toLocaleString('en-IN');
        }

        function renderOrderSummary() {
            if (!Array.isArray(cart) || cart.length === 0) {
                emptyCartMessage.style.display = 'block';
                checkoutForm.style.display = 'none';
                clearCartBtn.style.display = 'none';
                return;
            }

            let total = 0;
            let totalItems = 0;
            orderList.innerHTML = cart.map((item) => {
                const qty = Number(item.quantity || 1);
                const price = Number(item.price || 0);
                const itemTotal = qty * price;
                total += itemTotal;
                totalItems += qty;

                return `
                    <div class="order-item">
                        <div>
                            <strong>${item.name}</strong>
                            <small>Qty: ${qty} x ${formatCurrency(price)}</small>
                        </div>
                        <strong>${formatCurrency(itemTotal)}</strong>
                    </div>
                `;
            }).join('');

            totalItemsEl.textContent = totalItems;
            orderSubtotalEl.textContent = formatCurrency(total);
            orderTotal.textContent = formatCurrency(total);
        }

        clearCartBtn.addEventListener('click', () => {
            sessionStorage.removeItem('checkout_cart');
            window.location.reload();
        });

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
