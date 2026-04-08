<?php
require_once 'config/config.php';

if (isset($_SESSION['customer_logged_in']) && $_SESSION['customer_logged_in'] === true) {
    header('Location: index.html');
    exit;
}

$redirect = isset($_GET['redirect']) ? trim($_GET['redirect']) : 'index.html';
$allowedRedirects = ['checkout.php', 'index.html'];
if (!in_array($redirect, $allowedRedirects, true)) {
    $redirect = 'index.html';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Poonam Collection</title>
    <link rel="stylesheet" href="assets/css/auth.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="auth-shell">
        <div class="auth-card">
            <div class="brand">
                <img src="assets/images/logo.png?v=20260316" alt="Poonam Collection logo">
                <div>
                    <h1>Poonam Collection</h1>
                    <p>Account Access</p>
                </div>
            </div>

            <h2 class="auth-title">Login</h2>
            <p class="auth-subtitle">Login to continue to the home page.</p>

            <div class="alert error" id="errorBox"></div>

            <form id="loginForm">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit">Login</button>
            </form>

            <div class="auth-links">
                <a href="register.php?redirect=<?php echo urlencode($redirect); ?>">Create new account</a>
                <a href="admin/login.php">Admin login</a>
                <a href="index.html">Back to shop</a>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('loginForm');
        const errorBox = document.getElementById('errorBox');
        const redirectTo = <?php echo json_encode($redirect); ?>;

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            errorBox.style.display = 'none';

            const formData = new FormData(form);
            const payload = {
                email: formData.get('email'),
                password: formData.get('password')
            };

            try {
                const response = await fetch('api/auth.php?action=login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const result = await response.json();

                if (!result.success) {
                    throw new Error(result.message || 'Login failed');
                }

                window.location.href = redirectTo;
            } catch (error) {
                errorBox.textContent = error.message;
                errorBox.style.display = 'block';
            }
        });
    </script>
</body>
</html>
