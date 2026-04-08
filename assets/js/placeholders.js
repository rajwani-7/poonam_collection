// Placeholder Image URLs for Testing
// Use these if you don't have product images yet

const placeholderImages = {
    kurtas: [
        'https://via.placeholder.com/800x1000/d4af37/ffffff?text=Silk+Kurta',
        'https://via.placeholder.com/800x1000/b8941f/ffffff?text=Cotton+Kurta',
        'https://via.placeholder.com/800x1000/f39c12/ffffff?text=Designer+Kurta',
        'https://via.placeholder.com/800x1000/e74c3c/ffffff?text=Pathani+Suit'
    ],
    sherwanis: [
        'https://via.placeholder.com/800x1000/8e44ad/ffffff?text=Wedding+Sherwani',
        'https://via.placeholder.com/800x1000/9b59b6/ffffff?text=Designer+Sherwani',
        'https://via.placeholder.com/800x1000/c0392b/ffffff?text=Royal+Sherwani',
        'https://via.placeholder.com/800x1000/e74c3c/ffffff?text=Embroidered+Sherwani'
    ],
    'indo-western': [
        'https://via.placeholder.com/800x1000/3498db/ffffff?text=Indo+Western+Jacket',
        'https://via.placeholder.com/800x1000/2980b9/ffffff?text=Bandhgala+Suit',
        'https://via.placeholder.com/800x1000/1abc9c/ffffff?text=Fusion+Jacket',
        'https://via.placeholder.com/800x1000/16a085/ffffff?text=Modern+Kurta+Set'
    ],
    jackets: [
        'https://via.placeholder.com/800x1000/e67e22/ffffff?text=Nehru+Jacket',
        'https://via.placeholder.com/800x1000/d35400/ffffff?text=Ethnic+Jacket',
        'https://via.placeholder.com/800x1000/27ae60/ffffff?text=Velvet+Jacket',
        'https://via.placeholder.com/800x1000/16a085/ffffff?text=Designer+Jacket'
    ],
    categories: {
        kurtas: 'https://via.placeholder.com/600x800/d4af37/ffffff?text=Kurtas+Collection',
        sherwanis: 'https://via.placeholder.com/600x800/8e44ad/ffffff?text=Sherwanis+Collection',
        'indo-western': 'https://via.placeholder.com/600x800/3498db/ffffff?text=Indo+Western',
        jackets: 'https://via.placeholder.com/600x800/e67e22/ffffff?text=Ethnic+Jackets'
    },
    about: 'https://via.placeholder.com/800x1000/2c3e50/d4af37?text=About+Us',
    hero: 'https://via.placeholder.com/1920x1080/667eea/ffffff?text=Poonam+Collection'
};

// Free Stock Photo Sources for Men's Ethnic Wear
const freeImageSources = [
    {
        name: 'Unsplash',
        url: 'https://unsplash.com/s/photos/indian-men-ethnic',
        note: 'High-quality free images'
    },
    {
        name: 'Pexels',
        url: 'https://www.pexels.com/search/kurta/',
        note: 'Free stock photos'
    },
    {
        name: 'Pixabay',
        url: 'https://pixabay.com/images/search/sherwani/',
        note: 'Free images and videos'
    },
    {
        name: 'Burst by Shopify',
        url: 'https://burst.shopify.com/mens-fashion',
        note: 'Free for commercial use'
    }
];

// Image Size Recommendations
const imageSizes = {
    products: {
        recommended: '1000x1000px',
        minimum: '800x800px',
        format: 'JPG or PNG',
        maxSize: '5MB per image'
    },
    categories: {
        recommended: '800x600px',
        minimum: '600x400px',
        format: 'JPG',
        maxSize: '3MB per image'
    },
    hero: {
        recommended: '1920x1080px',
        minimum: '1600x900px',
        format: 'JPG',
        maxSize: '5MB'
    }
};

// Export for use
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        placeholderImages,
        freeImageSources,
        imageSizes
    };
}
