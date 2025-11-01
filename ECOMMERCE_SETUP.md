# E-Commerce Setup Guide

## Quick Setup Steps

Follow these steps to set up the e-commerce system:

### 1. Install Dependencies (if not already done)
```bash
composer install
npm install
```

### 2. Run Database Migrations
This will create all necessary tables for the e-commerce system:
```bash
php artisan migrate
```

The following tables will be created:
- `categories` - Product categories
- `products` - Products catalog
- `carts` - Shopping cart items
- `orders` - Customer orders
- `order_items` - Order line items

### 3. Seed Sample Data
Populate the database with sample products and categories:
```bash
php artisan db:seed --class=ProductSeeder
```

This will create:
- 5 product categories (Electronics, Fashion, Home & Living, Books, Sports)
- 15 sample products (3 products per category)

### 4. Create Storage Link
This is needed to serve uploaded product images:
```bash
php artisan storage:link
```

### 5. Start the Development Server
```bash
php artisan serve
```

### 6. Access the Application

Visit the following URLs:

**Product Catalog:**
- http://localhost:8000/products

**Shopping Cart (requires login):**
- http://localhost:8000/cart

**Orders (requires login):**
- http://localhost:8000/orders

**Register/Login:**
- http://localhost:8000/register
- http://localhost:8000/login

## Testing the E-Commerce Flow

### Step 1: Register/Login
1. Go to http://localhost:8000/register
2. Create a new account
3. Login with your credentials

### Step 2: Browse Products
1. Navigate to Products from the menu
2. Browse available products
3. Use filters to search by category
4. Click on a product to view details

### Step 3: Add to Cart
1. On product detail page, select quantity
2. Click "Add to Cart"
3. Notice cart counter updates in navigation
4. Click cart icon to view cart

### Step 4: Checkout
1. In cart, click "Proceed to Checkout"
2. Fill in shipping information:
   - Shipping address
   - Phone number
   - Order notes (optional)
3. Select payment method:
   - Credit Card
   - Bank Transfer
   - Cash on Delivery
4. Click "Place Order"

### Step 5: Payment Simulation
1. You'll be redirected to payment page
2. Review order details
3. Click "Complete Payment (Simulation)"
4. Payment status will update to "paid"

### Step 6: View Orders
1. Navigate to "My Orders" from menu
2. View all your orders
3. Click on an order to see details
4. Test canceling a pending order

## Navigation Structure

After implementing the e-commerce system, your navigation menu includes:

**Main Menu (Top Navigation):**
- Dashboard
- Products (Catalog)
- My Orders

**User Dropdown:**
- Profile
- Log Out

**Cart Icon:**
- Shows cart item count
- Quick access to shopping cart

## File Structure Overview

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── ProductController.php
│   │   ├── CartController.php
│   │   ├── CheckoutController.php
│   │   └── OrderController.php
│   └── Policies/
│       ├── CartPolicy.php
│       └── OrderPolicy.php
├── Models/
│   ├── Category.php
│   ├── Product.php
│   ├── Cart.php
│   ├── Order.php
│   └── OrderItem.php
database/
├── migrations/
│   ├── 2024_11_01_000001_create_categories_table.php
│   ├── 2024_11_01_000002_create_products_table.php
│   ├── 2024_11_01_000003_create_carts_table.php
│   ├── 2024_11_01_000004_create_orders_table.php
│   └── 2024_11_01_000005_create_order_items_table.php
└── seeders/
    └── ProductSeeder.php
resources/views/
├── products/
│   ├── index.blade.php
│   └── show.blade.php
├── cart/
│   └── index.blade.php
├── checkout/
│   ├── index.blade.php
│   └── payment.blade.php
└── orders/
    ├── index.blade.php
    └── show.blade.php
routes/
└── web.php (updated with e-commerce routes)
```

## Available Routes

### Public Routes
```
GET  /products              - Product catalog
GET  /products/{slug}       - Product detail
```

### Authenticated Routes
```
GET    /cart                    - View cart
POST   /cart/add/{product}      - Add to cart
PATCH  /cart/{cart}             - Update cart item
DELETE /cart/{cart}             - Remove cart item
DELETE /cart                    - Clear cart

GET  /checkout                  - Checkout form
POST /checkout                  - Process checkout
GET  /checkout/payment/{order}  - Payment page
POST /checkout/payment/{order}  - Process payment

GET  /orders                    - Order history
GET  /orders/{order}            - Order detail
POST /orders/{order}/cancel     - Cancel order
```

## Common Issues & Solutions

### Issue: "Table not found" error
**Solution:** Run migrations
```bash
php artisan migrate
```

### Issue: No products showing
**Solution:** Run seeder
```bash
php artisan db:seed --class=ProductSeeder
```

### Issue: Cart not updating
**Solution:** 
1. Make sure you're logged in
2. Clear browser cache
3. Check browser console for JavaScript errors

### Issue: Payment simulation not working
**Solution:** 
1. Make sure order status is "pending"
2. Check that you own the order (authorization)

### Issue: Product images not loading
**Solution:** Create storage link
```bash
php artisan storage:link
```

## Next Steps

After setup, you can:

1. **Customize Products:** 
   - Edit `ProductSeeder.php` to add more products
   - Run `php artisan db:seed --class=ProductSeeder` again

2. **Add Product Images:**
   - Upload images to `storage/app/public/products/`
   - Update product records with image paths

3. **Customize Styling:**
   - Edit Blade templates in `resources/views/`
   - Modify Tailwind classes for custom design

4. **Add More Features:**
   - Implement product reviews
   - Add admin panel
   - Integrate real payment gateway
   - Add shipping calculation

## Support

For detailed documentation, see `ECOMMERCE_DOCUMENTATION.md`

For issues or questions, please open an issue on GitHub.
