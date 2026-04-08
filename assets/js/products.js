// Product Modal Management
const productModal = document.getElementById('productModal');
const productForm = document.getElementById('productForm');

function openAddProductModal() {
    document.getElementById('modalTitle').textContent = 'Add New Product';
    productForm.reset();
    document.getElementById('productId').value = '';
    productModal.classList.add('active');
}

function closeProductModal() {
    productModal.classList.remove('active');
}

function editProduct(productId) {
    document.getElementById('modalTitle').textContent = 'Edit Product';
    
    // Fetch product data
    fetch(`../api/products.php?id=${productId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const product = data.product;
                document.getElementById('productId').value = product.id;
                document.getElementById('productName').value = product.name;
                document.getElementById('productCategory').value = product.category;
                document.getElementById('productPrice').value = product.price;
                document.getElementById('productStock').value = product.stock;
                document.getElementById('productDescription').value = product.description || '';
                document.getElementById('productFeatured').checked = product.featured == 1;
                
                productModal.classList.add('active');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Failed to load product data', 'error');
        });
}

function deleteProduct(productId) {
    if (confirm('Are you sure you want to delete this product?')) {
        fetch('../api/products.php', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: productId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Product deleted successfully');
                setTimeout(() => location.reload(), 1500);
            } else {
                showNotification('Failed to delete product', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Failed to delete product', 'error');
        });
    }
}

// Handle Product Form Submit
productForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(productForm);
    const productData = {
        name: formData.get('name'),
        category: formData.get('category'),
        price: parseFloat(formData.get('price')),
        stock: parseInt(formData.get('stock')),
        description: formData.get('description'),
        featured: formData.get('featured') ? 1 : 0
    };
    
    const productId = document.getElementById('productId').value;
    const method = productId ? 'PUT' : 'POST';
    
    if (productId) {
        productData.id = parseInt(productId);
    }
    
    try {
        const response = await fetch('../api/products.php', {
            method: method,
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(productData)
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification(productId ? 'Product updated successfully' : 'Product added successfully');
            closeProductModal();
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification(data.message || 'Operation failed', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Failed to save product', 'error');
    }
});

// Image Management
const imageModal = document.getElementById('imageModal');
const imageUploadForm = document.getElementById('imageUploadForm');
const imageInput = document.getElementById('imageInput');

function manageImages(productId) {
    document.getElementById('imageProductId').value = productId;
    loadProductImages(productId);
    imageModal.classList.add('active');
}

function closeImageModal() {
    imageModal.classList.remove('active');
}

function loadProductImages(productId) {
    fetch(`../api/products.php?id=${productId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.product.images) {
                displayImages(data.product.images, productId);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function displayImages(images, productId) {
    const imagesGrid = document.getElementById('productImages');
    
    if (images.length === 0) {
        imagesGrid.innerHTML = '<p style="text-align: center; color: var(--text-light);">No images uploaded yet</p>';
        return;
    }
    
    imagesGrid.innerHTML = images.map((image, index) => `
        <div class="image-item">
            <img src="${image}" alt="Product Image ${index + 1}">
            <div class="image-actions">
                <button class="btn-icon" onclick="setPrimaryImage(${productId}, '${image}')">
                    <i class="fas fa-star"></i>
                </button>
                <button class="btn-icon btn-delete" onclick="deleteImage(${productId}, '${image}')">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `).join('');
}

// Handle Image Upload
imageInput.addEventListener('change', async (e) => {
    const files = e.target.files;
    const productId = document.getElementById('imageProductId').value;
    
    if (files.length === 0) return;
    
    for (let i = 0; i < files.length; i++) {
        const formData = new FormData();
        formData.append('image', files[i]);
        formData.append('product_id', productId);
        formData.append('display_order', i);
        
        try {
            const response = await fetch('../api/upload.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                showNotification(`Image ${i + 1} uploaded successfully`);
            } else {
                showNotification(`Failed to upload image ${i + 1}`, 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification(`Failed to upload image ${i + 1}`, 'error');
        }
    }
    
    // Reload images after upload
    setTimeout(() => {
        loadProductImages(productId);
        imageInput.value = '';
    }, 1000);
});

function setPrimaryImage(productId, imageUrl) {
    // Implementation for setting primary image
    showNotification('Primary image updated');
}

function deleteImage(productId, imageUrl) {
    if (confirm('Are you sure you want to delete this image?')) {
        // Implementation for deleting image
        showNotification('Image deleted successfully');
        loadProductImages(productId);
    }
}

// Search and Filter
const searchInput = document.getElementById('searchInput');
const categoryFilter = document.getElementById('categoryFilter');

if (searchInput) {
    searchInput.addEventListener('input', filterProducts);
}

if (categoryFilter) {
    categoryFilter.addEventListener('change', filterProducts);
}

function filterProducts() {
    const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
    const category = categoryFilter ? categoryFilter.value : '';
    const productCards = document.querySelectorAll('.product-card-admin');
    
    productCards.forEach(card => {
        const productName = card.querySelector('h3').textContent.toLowerCase();
        const productCategory = card.getAttribute('data-category');
        
        const matchesSearch = productName.includes(searchTerm);
        const matchesCategory = !category || productCategory === category;
        
        if (matchesSearch && matchesCategory) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

// Close modals on outside click
window.addEventListener('click', (e) => {
    if (e.target === productModal) {
        closeProductModal();
    }
    if (e.target === imageModal) {
        closeImageModal();
    }
});
