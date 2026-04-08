# Poonam Collection - Premium E-Commerce Website

A modern, feature-rich e-commerce website for ethnic wear with a complete admin panel. Built with HTML, CSS, JavaScript, PHP, and MySQL.

## 🎨 Features

### Frontend Features
- **Modern UI/UX Design** - Clean, elegant interface with smooth animations
- **Responsive Design** - Works perfectly on all devices
- **Dynamic Product Display** - Real-time product loading from database
- **Category Filtering** - Easy product browsing by categories
- **Shopping Cart** - Fully functional cart with add/remove items
- **Product Search** - Advanced search functionality
- **Smooth Animations** - AOS (Animate On Scroll) library integration
- **Contact Form** - Customer inquiry form

### Admin Panel Features
- **Secure Login System** - Password protected admin access
- **Dashboard** - Overview of sales, orders, and products
- **Product Management** - Complete CRUD operations
  - Add new products
  - Edit existing products
  - Delete products
  - Update pricing and stock
- **Multi-Image Upload** - Add multiple images per product
- **Image Management** - Set primary images, delete images
- **Order Management** - View and manage customer orders
- **Category Management** - Organize products by categories
- **Real-time Statistics** - Sales analytics and reports

## 🛠️ Technology Stack

### Frontend
- HTML5
- CSS3 (Custom animations, Flexbox, Grid)
- JavaScript (ES6+)
- Font Awesome Icons
- Google Fonts (Poppins, Playfair Display)
- AOS Animation Library

### Backend
- PHP 7.4+
- MySQL Database
- PDO (PHP Data Objects)
- RESTful API

## 📋 Prerequisites

Before installation, ensure you have:
- XAMPP/WAMP/LAMP (Apache + PHP + MySQL)
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web browser (Chrome, Firefox, Edge)

## 🚀 Installation Guide

### Step 1: Setup Web Server
1. Install XAMPP from https://www.apachefriends.org/
2. Start Apache and MySQL services

### Step 2: Clone/Copy Project
1. Copy the `pc` folder to your web server directory:
   - For XAMPP: `C:\xampp\htdocs\pc`
   - For WAMP: `C:\wamp\www\pc`

### Step 3: Create Database
1. Open the MySQL tool from your hosting panel.
2. Create the database and user for this site.
3. Import the database:
   - Open the new database
   - Go to the "Import" tab
   - Select `database/setup.sql`
   - Click "Go"

### Step 4: Configure Database Connection
1. Open `config/database.php`
2. Update the hosting credentials if needed:
```php
define('DB_HOST', 'your-mysql-host');
define('DB_USER', 'your-mysql-user');
define('DB_PASS', 'your-mysql-password');
define('DB_NAME', 'your-mysql-database');
```
3. If your site is not in the domain root, set `APP_BASE_URL` to your full site URL.

### Step 5: Create Upload Directory
1. Create an `uploads` folder in the project root
2. Set proper permissions (777 on Linux/Mac)

### Step 6: Access the Website

#### Frontend
Open: http://localhost/pc/

#### Admin Panel
1. Open: http://localhost/pc/admin/login.php
2. Default credentials:
   - **Username:** admin
   - **Password:** admin123

### InfinityFree Notes
- Upload the project files to your hosting account.
- Import `database/setup.sql` into the MySQL database created in your panel.
- Update `config/database.php` with the InfinityFree database host, username, password, and database name.
- `config/config.php` now detects the site URL automatically, but you can override it with `APP_BASE_URL` if needed.

## 📂 Project Structure

```
pc/
├── admin/                  # Admin panel
│   ├── index.php          # Dashboard
│   ├── products.php       # Product management
│   ├── login.php          # Admin login
│   └── logout.php         # Logout handler
├── api/                   # Backend API
│   ├── products.php       # Product CRUD operations
│   └── upload.php         # Image upload handler
├── assets/                # Static assets
│   ├── css/
│   │   ├── style.css      # Frontend styles
│   │   └── admin.css      # Admin panel styles
│   ├── js/
│   │   ├── main.js        # Frontend logic
│   │   ├── admin.js       # Admin common functions
│   │   └── products.js    # Product management logic
│   └── images/            # Static images
├── config/                # Configuration files
│   ├── database.php       # Database connection
│   └── config.php         # General settings
├── database/              # Database files
│   └── setup.sql          # Database schema & sample data
├── uploads/               # User uploaded images
├── index.html             # Homepage
└── README.md             # This file
```

## 🎯 Key Functionalities

### Admin Panel Operations

#### 1. Add Product
- Click "Add Product" button
- Fill in product details:
  - Name
  - Category
  - Price
  - Stock quantity
  - Description
  - Featured status
- Submit form

#### 2. Edit Product
- Click edit icon on product card
- Update product information
- Save changes

#### 3. Delete Product
- Click delete icon on product card
- Confirm deletion
- Product and associated images removed

#### 4. Manage Images
- Click images icon on product card
- Upload multiple images (drag & drop or select)
- Set primary image
- Delete unwanted images

#### 5. Search & Filter
- Use search box to find products by name
- Filter by category using dropdown
- Real-time results update

## 🎨 Design Features

### UI/UX Highlights
- **Golden Theme** - Elegant gold (#d4af37) primary color
- **Smooth Transitions** - 0.4s cubic-bezier animations
- **Glassmorphism** - Modern frosted glass effects
- **Gradient Backgrounds** - Dynamic gradient animations
- **Card Hover Effects** - Interactive product cards
- **Modal Dialogs** - Smooth modal animations
- **Responsive Grid** - Auto-fit grid layouts
- **Custom Scrollbars** - Styled scrollbars

### Animation Effects
- Fade-up animations on scroll
- Hover scale transformations
- Smooth color transitions
- Bouncing scroll indicator
- Slide-in notifications
- Gradient shifting backgrounds

## 🔐 Security Features

- Password hashing (PHP password_hash)
- Session management
- SQL injection prevention (PDO prepared statements)
- XSS protection
- CSRF token support ready
- File upload validation
- Admin authentication

## 📱 Responsive Breakpoints

- Desktop: 1200px+
- Tablet: 768px - 1199px
- Mobile: < 768px

## 🌐 Browser Support

- Chrome (recommended)
- Firefox
- Safari
- Edge
- Opera

## 📊 Database Structure

### Tables
1. **products** - Product information
2. **product_images** - Multiple product images
3. **admin_users** - Admin authentication
4. **orders** - Customer orders
5. **order_items** - Order details

## 🔧 Customization

### Change Colors
Edit `assets/css/style.css` and `assets/css/admin.css`:
```css
:root {
    --primary-color: #d4af37;  /* Change this */
    --secondary-color: #2c3e50; /* And this */
}
```

### Add Categories
1. Update dropdown in `admin/products.php`
2. Update filter in frontend
3. Add to database if using category table

### Modify Upload Limits
Edit `api/upload.php`:
```php
$maxFileSize = 5 * 1024 * 1024; // 5MB
```

## 🐛 Troubleshooting

### Issue: Database Connection Failed
- Check MySQL is running
- Verify database credentials in `config/database.php`
- Ensure database `poonam_collection` exists

### Issue: Images Not Uploading
- Check `uploads/` folder exists
- Verify folder permissions (777)
- Check PHP upload limits in `php.ini`

### Issue: Admin Login Not Working
- Verify admin user exists in database
- Check password hash is correct
- Clear browser cache/cookies

### Issue: Blank Pages
- Enable error reporting in `config/config.php`
- Check Apache error logs
- Verify PHP version compatibility

## 📈 Future Enhancements

- Customer registration/login
- Order tracking system
- Payment gateway integration
- Email notifications
- Product reviews and ratings
- Wishlist functionality
- Advanced analytics
- Multi-language support
- Export reports to PDF/Excel

## 👨‍💻 Development

### To Add New Features
1. Create new API endpoint in `api/`
2. Add frontend logic in `assets/js/`
3. Update UI in respective PHP/HTML files
4. Test thoroughly

### To Modify Styles
1. Edit CSS files in `assets/css/`
2. Use CSS variables for consistency
3. Test on multiple screen sizes

## 📞 Support

For issues or questions:
1. Check troubleshooting section
2. Review code comments
3. Test with sample data

## 📄 License

This project is free to use for personal and commercial purposes.

## 🙏 Credits

- Font Awesome for icons
- Google Fonts for typography
- AOS Library for animations

## 🎉 Conclusion

You now have a fully functional e-commerce website with admin panel! The system includes:
- ✅ Modern, responsive design
- ✅ Complete product management
- ✅ Multi-image support
- ✅ Secure admin panel
- ✅ Smooth animations
- ✅ Database integration

**Happy Selling! 🛍️**

---

**Version:** 1.0.0  
**Last Updated:** March 10, 2026  
**Built with:** ❤️ and ☕
