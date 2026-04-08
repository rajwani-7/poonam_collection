# Poonam Collection - Men's Fashion Website Setup

## ✅ What's Been Done

The website has been successfully updated for **Poonam Collection - Men's Fashion**:

1. **Branding Updated**
   - Changed tagline to "Premium Men's Fashion"
   - Updated hero section and About Us content
   - Modified footer descriptions

2. **Categories Changed to Men's Clothing**
   - Kurtas (Traditional elegance)
   - Sherwanis (Wedding collection)
   - Indo-Western (Modern fusion)
   - Ethnic Jackets (Contemporary style)

3. **Database Updated**
   - 8 sample men's products added:
     - Royal Silk Kurta (₹2,499)
     - Designer Sherwani (₹12,999)
     - Indo-Western Jacket Set (₹5,499)
     - Cotton Kurta Pajama (₹1,299)
     - Nehru Jacket (₹2,999)
     - Pathani Suit (₹1,799)
     - Bandhgala Suit (₹8,999)
     - Embroidered Sherwani (₹15,999)

4. **Placeholder Images Created**
   - ✅ Category images: 4 images (600x800px)
   - ✅ Product images: 17 images (800x1000px)
   - ✅ About page image: 1 image
   - All images have "POONAM COLLECTION" watermark

## 📂 File Locations

### Category Images
- `assets/images/categories/kurtas.jpg`
- `assets/images/categories/sherwanis.jpg`
- `assets/images/categories/indo-western.jpg`
- `assets/images/categories/jackets.jpg`

### Product Images (in uploads/)
- `kurta1-1.jpg`, `kurta1-2.jpg`, `kurta1-3.jpg` (Royal Silk Kurta)
- `kurta2-1.jpg`, `kurta2-2.jpg` (Cotton Kurta)
- `kurta3-1.jpg` (Pathani Suit)
- `sherwani1-1.jpg`, `sherwani1-2.jpg`, `sherwani1-3.jpg` (Designer)
- `sherwani2-1.jpg`, `sherwani2-2.jpg` (Embroidered)
- `indo-western1-1.jpg`, `indo-western1-2.jpg` (Jacket Set)
- `bandhgala1-1.jpg`, `bandhgala1-2.jpg` (Bandhgala Suit)
- `jacket1-1.jpg`, `jacket1-2.jpg` (Nehru Jacket)

## 🚀 Next Steps to Run the Website

### 1. Start XAMPP
```
- Open XAMPP Control Panel
- Start Apache
- Start MySQL
```

### 2. Setup Database
Open your browser and go to:
```
http://localhost/pc/setup.php
```
This will:
- Create the database `poonam_collection`
- Create all tables
- Insert sample products
- Create admin user

### 3. Access the Website
**Frontend (Customer view):**
```
http://localhost/pc/
```

**Admin Panel:**
```
http://localhost/pc/admin/
Username: admin
Password: admin123
```

## 📸 Replacing Placeholder Images

When you have real product photos:

1. Go to Admin Panel → Products
2. Click on a product
3. Click the "Manage Images" button (camera icon)
4. Upload your real product photos
5. Set one as primary image

### Recommended Image Sizes:
- **Product Images:** 800x800px minimum, 1000x1000px recommended
- **Category Images:** 600x800px
- **Format:** JPG or PNG
- **Max Size:** 5MB per image

## 🎨 Image Sources for Real Photos

If you need real product images, check these free stock photo sites:
- [Unsplash](https://unsplash.com/s/photos/indian-men-ethnic) - High-quality free images
- [Pexels](https://www.pexels.com/search/kurta/) - Free stock photos
- [Pixabay](https://pixabay.com/images/search/sherwani/) - Free images
- [Burst by Shopify](https://burst.shopify.com/mens-fashion) - Free for commercial use

## 🛠️ Admin Features

From the admin panel, you can:
- ✅ Add new products
- ✅ Edit existing products
- ✅ Upload multiple images per product
- ✅ Mark products as featured
- ✅ Manage stock levels
- ✅ Set prices
- ✅ Delete products

## 📱 Website Features

The website includes:
- Responsive design (mobile, tablet, desktop)
- Product filtering by category
- Shopping cart functionality
- Search functionality
- Smooth animations
- Contact form
- Featured products section

## 🔧 Files Modified

1. **index.html** - Updated branding and categories
2. **database/setup.sql** - Changed to men's products
3. **admin/products.php** - Updated category dropdowns
4. **assets/js/placeholders.js** - Updated placeholder URLs

## ⚡ Quick Test

1. Open http://localhost/pc/
2. You should see:
   - "Poonam Collection - Premium Men's Fashion" in header
   - 4 categories: Kurtas, Sherwanis, Indo-Western, Jackets
   - Placeholder images with "POONAM COLLECTION" watermark

3. Run setup.php to populate the database
4. Products will appear on the homepage

## 🎯 Success!

Your website is now ready for men's ethnic wear! All placeholder images are in place, and you can start replacing them with real photos whenever you're ready.
