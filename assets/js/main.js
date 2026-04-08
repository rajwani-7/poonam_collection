// Initialize AOS (Animate On Scroll)
AOS.init({
    duration: 800,
    easing: 'ease-in-out',
    once: true,
    offset: 100
});

// Navbar Scroll Effect
const navbar = document.getElementById('navbar');
let lastScroll = 0;

window.addEventListener('scroll', () => {
    const currentScroll = window.pageYOffset;
    
    if (currentScroll > 100) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
    
    lastScroll = currentScroll;
});

// Mobile Menu Toggle
const menuToggle = document.getElementById('menuToggle');
const navLinks = document.getElementById('navLinks');
const overlay = document.getElementById('overlay');

menuToggle.addEventListener('click', () => {
    navLinks.classList.toggle('active');
    overlay.classList.toggle('active');
});

overlay.addEventListener('click', () => {
    navLinks.classList.remove('active');
    overlay.classList.remove('active');
    cartSidebar.classList.remove('active');
    searchOverlay.classList.remove('active');
});

// Smooth Scrolling for Navigation Links
document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', (e) => {
        e.preventDefault();
        const target = document.querySelector(link.getAttribute('href'));
        
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
            
            // Close mobile menu
            navLinks.classList.remove('active');
            overlay.classList.remove('active');
            
            // Update active link
            document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
            link.classList.add('active');
        }
    });
});

// Search Overlay
const searchBtn = document.getElementById('searchBtn');
const searchOverlay = document.getElementById('searchOverlay');
const closeSearch = document.getElementById('closeSearch');
const accountBtn = document.getElementById('accountBtn');

searchBtn.addEventListener('click', () => {
    searchOverlay.classList.add('active');
    document.getElementById('searchInput').focus();
});

closeSearch.addEventListener('click', () => {
    searchOverlay.classList.remove('active');
});

async function initializeAccountButton() {
    if (!accountBtn) {
        return;
    }

    try {
        const response = await fetch('api/auth.php?action=status');
        const data = await response.json();

        if (data.success && data.logged_in) {
            accountBtn.href = 'checkout.php';
            accountBtn.title = `Account: ${data.customer.name}`;
        } else {
            accountBtn.href = 'login.php';
            accountBtn.title = 'Login / Account';
        }
    } catch (error) {
        accountBtn.href = 'login.php';
        accountBtn.title = 'Login / Account';
    }
}

// Cart Sidebar
const cartBtn = document.getElementById('cartBtn');
const cartSidebar = document.getElementById('cartSidebar');
const closeCart = document.getElementById('closeCart');
const checkoutBtn = document.querySelector('.checkout-btn');
let cart = [];

cartBtn.addEventListener('click', () => {
    cartSidebar.classList.add('active');
    overlay.classList.add('active');
    updateCartDisplay();
});

closeCart.addEventListener('click', () => {
    cartSidebar.classList.remove('active');
    overlay.classList.remove('active');
});

if (checkoutBtn) {
    checkoutBtn.addEventListener('click', handleCheckout);
}

async function handleCheckout() {
    if (cart.length === 0) {
        showNotification('Your cart is empty');
        return;
    }

    sessionStorage.setItem('checkout_cart', JSON.stringify(cart));

    try {
        const response = await fetch('api/auth.php?action=status');
        const data = await response.json();

        if (data.success && data.logged_in) {
            window.location.href = 'checkout.php';
            return;
        }
    } catch (error) {
        console.error('Auth check failed:', error);
    }

    window.location.href = 'login.php?redirect=checkout.php';
}

// Load Products
let allProducts = [];
let currentFilter = 'all';
let currentPage = 1;
const productsPerPage = 12;
const fallbackImage = 'assets/images/image.png';

function normalizeCategory(value) {
    return String(value || '')
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, '-');
}

function getProductImage(product) {
    return product && product.image ? product.image : fallbackImage;
}

function handleImageError(imageElement) {
    if (!imageElement || imageElement.src.includes(fallbackImage)) {
        return;
    }
    imageElement.onerror = null;
    imageElement.src = fallbackImage;
}

window.handleImageError = handleImageError;

async function loadProducts() {
    try {
        const response = await fetch('api/products.php');
        const data = await response.json();
        
        if (data.success && Array.isArray(data.products) && data.products.length > 0) {
            allProducts = data.products;
            displayProducts();
        } else {
            displaySampleProducts();
        }
    } catch (error) {
        console.error('Error loading products:', error);
        // Display sample products if API fails
        displaySampleProducts();
    }
}

function displaySampleProducts() {
    const sampleProducts = [
        {
            id: 1,
            name: 'Oxford Formal Shirt',
            category: 'Shirts',
            price: 1899,
            image: 'uploads/kurta1-1.jpg',
            featured: true
        },
        {
            id: 2,
            name: 'Classic Crew Neck T-Shirt',
            category: 'T-Shirts',
            price: 799,
            image: 'uploads/kurta2-1.jpg',
            featured: true
        },
        {
            id: 3,
            name: 'Slim Fit Chino Pants',
            category: 'Pants',
            price: 1699,
            image: 'uploads/indo-western1-1.jpg',
            featured: true
        },
        {
            id: 4,
            name: 'Dark Blue Stretch Jeans',
            category: 'Jeans',
            price: 2199,
            image: 'uploads/jacket1-1.jpg',
            featured: false
        },
        {
            id: 5,
            name: 'Casual Checked Shirt',
            category: 'Shirts',
            price: 1399,
            image: 'uploads/kurta3-1.jpg',
            featured: false
        },
        {
            id: 6,
            name: 'Relaxed Fit Jeans',
            category: 'Jeans',
            price: 1999,
            image: 'uploads/bandhgala1-1.jpg',
            featured: false
        }
    ];
    
    allProducts = sampleProducts;
    displayProducts();
}

function displayProducts() {
    const productsGrid = document.getElementById('productsGrid');
    let filteredProducts = allProducts;
    
    // Apply filter
    if (currentFilter !== 'all') {
        filteredProducts = allProducts.filter(p => 
            normalizeCategory(p.category) === normalizeCategory(currentFilter)
        );
    }
    
    // Pagination
    const startIndex = (currentPage - 1) * productsPerPage;
    const endIndex = startIndex + productsPerPage;
    const paginatedProducts = filteredProducts.slice(startIndex, endIndex);
    
    productsGrid.innerHTML = paginatedProducts.map(product => `
        <div class="product-card" data-aos="fade-up">
            <div class="product-image">
                <img src="${getProductImage(product)}" alt="${product.name}" loading="lazy" onerror="handleImageError(this)">
                ${product.featured ? '<span class="product-badge">Featured</span>' : ''}
                <div class="product-actions">
                    <button class="product-action-btn" onclick="quickView(${product.id})">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="product-action-btn" onclick="addToWishlist(${product.id})">
                        <i class="fas fa-heart"></i>
                    </button>
                </div>
            </div>
            <div class="product-info">
                <div class="product-category">${product.category}</div>
                <h3 class="product-name">${product.name}</h3>
                <div class="product-price">
                    <span class="current-price">₹${product.price.toLocaleString()}</span>
                </div>
                <button class="add-to-cart-btn" onclick="addToCart(${product.id})">
                    <i class="fas fa-shopping-cart"></i> Add to Cart
                </button>
            </div>
        </div>
    `).join('');
    
    // Show/hide load more button
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    if (endIndex >= filteredProducts.length) {
        loadMoreBtn.style.display = 'none';
    } else {
        loadMoreBtn.style.display = 'inline-block';
    }
}

// Filter Products
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        currentFilter = this.getAttribute('data-filter');
        currentPage = 1;
        displayProducts();
    });
});

// Load More Products
document.getElementById('loadMoreBtn').addEventListener('click', () => {
    currentPage++;
    const productsGrid = document.getElementById('productsGrid');
    const currentHTML = productsGrid.innerHTML;
    displayProducts();
    productsGrid.innerHTML = currentHTML + productsGrid.innerHTML;
});

// Add to Cart
function addToCart(productId) {
    const product = allProducts.find(p => p.id === productId);
    
    if (product) {
        const existingItem = cart.find(item => item.id === productId);
        
        if (existingItem) {
            existingItem.quantity++;
        } else {
            cart.push({
                ...product,
                quantity: 1
            });
        }
        
        updateCartCount();
        updateCartDisplay();
        showNotification('Product added to cart!');
    }
}

function updateCartCount() {
    const cartCount = document.querySelector('.cart-count');
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    cartCount.textContent = totalItems;
}

function updateCartDisplay() {
    const cartItems = document.getElementById('cartItems');
    const totalAmount = document.querySelector('.total-amount');
    
    if (cart.length === 0) {
        cartItems.innerHTML = '<p class="empty-cart">Your cart is empty</p>';
        totalAmount.textContent = '₹0.00';
        return;
    }
    
    cartItems.innerHTML = cart.map(item => `
        <div class="cart-item">
            <img src="${getProductImage(item)}" alt="${item.name}" loading="lazy" onerror="handleImageError(this)">
            <div class="cart-item-info">
                <h4>${item.name}</h4>
                <p>₹${item.price.toLocaleString()}</p>
                <div class="quantity-controls">
                    <button onclick="updateQuantity(${item.id}, -1)">-</button>
                    <span>${item.quantity}</span>
                    <button onclick="updateQuantity(${item.id}, 1)">+</button>
                </div>
            </div>
            <button class="remove-item" onclick="removeFromCart(${item.id})">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `).join('');
    
    const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    totalAmount.textContent = `₹${total.toLocaleString()}`;
}

function updateQuantity(productId, change) {
    const item = cart.find(i => i.id === productId);
    
    if (item) {
        item.quantity += change;
        
        if (item.quantity <= 0) {
            removeFromCart(productId);
        } else {
            updateCartCount();
            updateCartDisplay();
        }
    }
}

function removeFromCart(productId) {
    cart = cart.filter(item => item.id !== productId);
    updateCartCount();
    updateCartDisplay();
}

// Quick View
function quickView(productId) {
    const product = allProducts.find(p => p.id === productId);
    if (product) {
        alert(`Quick View: ${product.name}\nPrice: ₹${product.price}\nCategory: ${product.category}`);
        // Implement modal for quick view
    }
}

// Wishlist
function addToWishlist(productId) {
    showNotification('Added to wishlist!');
}

// Notification
function showNotification(message) {
    const notification = document.createElement('div');
    notification.className = 'notification';
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background: var(--primary-color);
        color: white;
        padding: 15px 25px;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        z-index: 3000;
        animation: slideIn 0.3s ease;
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Contact Form
document.getElementById('contactForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    
    try {
        // Send form data to backend
        showNotification('Message sent successfully!');
        e.target.reset();
    } catch (error) {
        showNotification('Failed to send message. Please try again.');
    }
});

// Search Functionality
document.getElementById('searchInput').addEventListener('input', (e) => {
    const searchTerm = e.target.value.toLowerCase();
    
    if (searchTerm.length > 2) {
        const results = allProducts.filter(product =>
            product.name.toLowerCase().includes(searchTerm) ||
            product.category.toLowerCase().includes(searchTerm)
        );
        
        // Display search results
        console.log('Search results:', results);
    }
});

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    initializeAccountButton();
    loadProducts();
    
    // Add CSS animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
        
        .cart-item {
            display: flex;
            gap: 15px;
            padding: 20px;
            border-bottom: 1px solid var(--border-color);
            position: relative;
        }
        
        .cart-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }
        
        .cart-item-info {
            flex: 1;
        }
        
        .cart-item-info h4 {
            font-size: 16px;
            margin-bottom: 5px;
        }
        
        .cart-item-info p {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .quantity-controls {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        .quantity-controls button {
            width: 30px;
            height: 30px;
            border: 1px solid var(--border-color);
            background: white;
            border-radius: 5px;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .quantity-controls button:hover {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }
        
        .remove-item {
            position: absolute;
            top: 10px;
            right: 10px;
            background: transparent;
            border: none;
            font-size: 18px;
            color: var(--text-light);
            cursor: pointer;
            transition: var(--transition);
        }
        
        .remove-item:hover {
            color: red;
        }
    `;
    document.head.appendChild(style);
});
