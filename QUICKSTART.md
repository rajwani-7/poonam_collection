# 🚀 Quick Start Guide - Poonam Collection

## ⚡ 5-Minute Setup

### 1. Install XAMPP
Download and install XAMPP from: https://www.apachefriends.org/

### 2. Start Services
- Open XAMPP Control Panel
- Click "Start" for Apache
- Click "Start" for MySQL

### 3. Setup Database
1. Open browser and go to: `http://localhost/phpmyadmin`
2. Click "New" to create database
3. Database name: `poonam_collection`
4. Click "Create"
5. Select the database you just created
6. Go to "Import" tab
7. Click "Choose File" and select: `database/setup.sql`
8. Click "Go" at bottom

### 4. Copy Project Files
Copy the entire `pc` folder to:
- **Windows XAMPP:** `C:\xampp\htdocs\pc`
- **Mac XAMPP:** `/Applications/XAMPP/htdocs/pc`
- **Linux:** `/opt/lampp/htdocs/pc`

### 5. Run Setup
Open browser and go to: `http://localhost/pc/setup.php`

This will check:
- ✓ PHP version
- ✓ Database connection
- ✓ Upload directory
- ✓ File permissions

### 6. Access Your Website

#### 🌐 Frontend (Customer Site)
```
http://localhost/pc/
```

#### 🔧 Admin Panel
```
URL: http://localhost/pc/admin/
Username: admin
Password: admin123
```

---

## 📌 Common Tasks

### Add a New Product
1. Login to admin panel
2. Click "Products" in sidebar
3. Click "Add Product" button
4. Fill in details:
   - Product name
   - Category
   - Price
   - Stock quantity
   - Description
5. Click "Save Product"
6. Click images icon to upload product photos

### Upload Product Images
1. Go to Products page
2. Click the images icon (🖼️) on product card
3. Click or drag-and-drop images
4. Multiple images can be uploaded at once
5. Set primary image by clicking star icon

### Edit Product
1. Click edit icon (✏️) on product card
2. Modify any field
3. Click "Save Product"

### Delete Product
1. Click delete icon (🗑️) on product card
2. Confirm deletion
3. Product and all images will be removed

---

## 🎨 Customization

### Change Website Colors
Edit `assets/css/style.css` - Line 1-12:
```css
:root {
    --primary-color: #d4af37;  /* Gold - Change this */
    --secondary-color: #2c3e50; /* Dark blue - Change this */
}
```

### Change Logo/Brand Name
Edit `index.html` - Line 18-20:
```html
<h1>Poonam Collection</h1>
<p class="tagline">Premium Ethnic Wear</p>
```

### Add New Category
1. Edit `admin/products.php` - Find line ~120
2. Add your category in the select options
3. Do same in `index.html` filter section

---

## 🐛 Troubleshooting

### Problem: "Database connection failed"
**Solution:**
- Check if MySQL is running in XAMPP
- Verify database name is exactly: `poonam_collection`
- Check credentials in `config/database.php`

### Problem: "Cannot upload images"
**Solution:**
- Check if `uploads` folder exists
- Right-click uploads folder → Properties → Security
- Give "Full Control" permissions
- Restart Apache

### Problem: "Blank admin page"
**Solution:**
- Clear browser cache (Ctrl + Shift + Delete)
- Check if you ran database setup
- Verify admin user exists in database

### Problem: "Products not showing"
**Solution:**
- Check database has sample data
- Open browser console (F12) for errors
- Verify API endpoint: `http://localhost/pc/api/products.php`

---

## 📱 Test on Mobile

### Using Chrome DevTools
1. Open website: `http://localhost/pc/`
2. Press F12 to open DevTools
3. Click device icon (top-left)
4. Select device (iPhone, iPad, etc.)
5. Test responsive design

### On Real Phone (Same WiFi)
1. Find your computer's IP address:
   - Windows: Open CMD → type `ipconfig`
   - Mac/Linux: Open Terminal → type `ifconfig`
2. Look for IPv4 Address (e.g., 192.168.1.10)
3. On phone browser: `http://192.168.1.10/pc/`

---

## 🔒 Security (Before Going Live)

### Change Admin Password
1. Go to phpMyAdmin
2. Open `poonam_collection` database
3. Click `admin_users` table
4. Delete existing admin
5. Run this SQL:
```sql
INSERT INTO admin_users (username, password, email, full_name) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'your@email.com', 'Your Name');
```
Replace password hash with your bcrypt hash

### Update Database Credentials
Edit `config/database.php` with production credentials

### Enable HTTPS
Get SSL certificate (Let's Encrypt is free)

---

## 📊 Default Sample Data

The database includes 6 sample products:
1. Elegant Silk Saree - ₹2,999
2. Designer Kurti Set - ₹1,499
3. Bridal Lehenga - ₹15,999
4. Cotton Salwar Suit - ₹1,299
5. Party Wear Gown - ₹3,499
6. Casual Kurti - ₹799

**Note:** Sample products won't have images until you upload them in admin panel.

---

## 🎯 Next Steps

1. ✅ Setup complete
2. 📸 Upload real product images
3. ✏️ Edit sample products with your data
4. 🎨 Customize colors and branding
5. 📧 Add your contact information
6. 🚀 Launch your store!

---

## 💡 Tips for Success

### Product Photos
- Use high-quality images (1000x1000px minimum)
- White or plain background works best
- Show product from multiple angles
- Add 3-5 images per product

### Product Descriptions
- Highlight key features
- Mention fabric/material
- Include size information
- Add care instructions

### Pricing Strategy
- Research competitor prices
- Consider shipping costs
- Offer bundle deals
- Create featured products

---

## 📞 Need Help?

### Check These First
1. README.md - Detailed documentation
2. setup.php - Installation checker
3. Browser console (F12) - Error messages
4. Apache error logs - Server errors

### File Locations
- **Error Logs:** `C:\xampp\apache\logs\error.log`
- **PHP Config:** `C:\xampp\php\php.ini`
- **Database:** phpMyAdmin → poonam_collection

---

## ✨ Features Overview

### Customer Features
- 🛍️ Browse products by category
- 🔍 Search products
- 🛒 Shopping cart
- 📱 Mobile responsive
- ✨ Smooth animations
- 💳 Contact form

### Admin Features
- 📊 Sales dashboard
- ➕ Add products
- ✏️ Edit products
- 🗑️ Delete products
- 🖼️ Multi-image upload
- 📦 Manage orders
- 📈 View statistics

---

## 🎉 You're All Set!

Your e-commerce website is ready to use. Start by:
1. Logging into admin panel
2. Adding your products
3. Uploading beautiful images
4. Customizing colors and text
5. Sharing with customers!

**Happy Selling! 💰**

---

**Quick Links:**
- Frontend: http://localhost/pc/
- Admin: http://localhost/pc/admin/
- Setup Check: http://localhost/pc/setup.php
- Database: http://localhost/phpmyadmin

**Version:** 1.0.0  
**Last Updated:** March 10, 2026
