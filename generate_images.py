"""
Generate placeholder images for Poonam Collection - Men's Fashion
"""
from PIL import Image, ImageDraw, ImageFont
import os

# Create directories if they don't exist
os.makedirs('assets/images/categories', exist_ok=True)
os.makedirs('uploads', exist_ok=True)

def create_placeholder(width, height, text, color, filename):
    """Create a placeholder image with text"""
    # Create image with gradient background
    img = Image.new('RGB', (width, height), color=color)
    draw = ImageDraw.Draw(img)
    
    # Try to use a nice font, fallback to default
    try:
        font = ImageFont.truetype("arial.ttf", 60)
        small_font = ImageFont.truetype("arial.ttf", 30)
    except:
        font = ImageFont.load_default()
        small_font = ImageFont.load_default()
    
    # Add some design elements
    # Draw diagonal lines for texture
    for i in range(0, width + height, 50):
        draw.line([(i, 0), (0, i)], fill=(255, 255, 255, 30), width=2)
    
    # Draw centered text
    text_bbox = draw.textbbox((0, 0), text, font=font)
    text_width = text_bbox[2] - text_bbox[0]
    text_height = text_bbox[3] - text_bbox[1]
    x = (width - text_width) // 2
    y = (height - text_height) // 2
    
    # Add shadow
    draw.text((x+3, y+3), text, fill=(0, 0, 0, 128), font=font)
    # Add main text
    draw.text((x, y), text, fill='white', font=font)
    
    # Add "Poonam Collection" watermark at bottom
    watermark = "POONAM COLLECTION"
    wm_bbox = draw.textbbox((0, 0), watermark, font=small_font)
    wm_width = wm_bbox[2] - wm_bbox[0]
    wm_x = (width - wm_width) // 2
    draw.text((wm_x, height - 80), watermark, fill=(255, 255, 255, 180), font=small_font)
    
    # Save image
    img.save(filename, 'JPEG', quality=85)
    print(f"Created: {filename}")

# Category images (600x800)
categories = [
    ('Kurtas', '#d4af37', 'kurtas.jpg'),
    ('Sherwanis', '#8e44ad', 'sherwanis.jpg'),
    ('Indo-Western', '#3498db', 'indo-western.jpg'),
    ('Ethnic Jackets', '#e67e22', 'jackets.jpg'),
]

print("Creating category images...")
for name, color, filename in categories:
    create_placeholder(600, 800, name, color, f'assets/images/categories/{filename}')

# Product images (800x1000) - Multiple variations per category
products = [
    # Kurtas
    ('Royal Silk Kurta', '#d4af37', 'uploads/kurta1-1.jpg'),
    ('Royal Silk\nKurta\nBack View', '#c9a532', 'uploads/kurta1-2.jpg'),
    ('Royal Silk\nKurta\nDetail', '#b89b2d', 'uploads/kurta1-3.jpg'),
    ('Cotton Kurta', '#b8941f', 'uploads/kurta2-1.jpg'),
    ('Cotton Kurta\nSide View', '#a78519', 'uploads/kurta2-2.jpg'),
    ('Pathani Suit', '#f39c12', 'uploads/kurta3-1.jpg'),
    
    # Sherwanis
    ('Designer\nSherwani', '#8e44ad', 'uploads/sherwani1-1.jpg'),
    ('Designer\nSherwani\nDetail', '#7d3c98', 'uploads/sherwani1-2.jpg'),
    ('Designer\nSherwani\nFull View', '#6c3483', 'uploads/sherwani1-3.jpg'),
    ('Embroidered\nSherwani', '#9b59b6', 'uploads/sherwani2-1.jpg'),
    ('Embroidered\nSherwani\nDetail', '#884ea0', 'uploads/sherwani2-2.jpg'),
    
    # Indo-Western
    ('Indo-Western\nJacket Set', '#3498db', 'uploads/indo-western1-1.jpg'),
    ('Indo-Western\nDetail View', '#2e86c1', 'uploads/indo-western1-2.jpg'),
    ('Bandhgala Suit', '#2980b9', 'uploads/bandhgala1-1.jpg'),
    ('Bandhgala\nSide View', '#21618c', 'uploads/bandhgala1-2.jpg'),
    
    # Jackets
    ('Nehru Jacket', '#e67e22', 'uploads/jacket1-1.jpg'),
    ('Nehru Jacket\nBack View', '#ca6f1e', 'uploads/jacket1-2.jpg'),
]

print("\nCreating product images...")
for name, color, filename in products:
    create_placeholder(800, 1000, name, color, filename)

# About image
print("\nCreating about image...")
create_placeholder(800, 1000, 'About\nPoonam\nCollection', '#2c3e50', 'assets/images/about.jpg')

print("\n✅ All images created successfully!")
print("\nNext steps:")
print("1. Run setup.php to create the database")
print("2. The website now has placeholder images for all products")
print("3. You can replace these with real photos later")
