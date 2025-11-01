# E-Commerce System Documentation

## Overview
Sistem e-commerce yang telah ditambahkan ke aplikasi Laravel ini mencakup fitur-fitur lengkap untuk mengelola produk, keranjang belanja, checkout, dan pesanan.

## Fitur-Fitur

### 1. 🛍️ Katalog Produk
- **Product List**: Halaman daftar produk dengan filter dan pencarian
  - Filter berdasarkan kategori
  - Pencarian berdasarkan nama produk
  - Sorting (terbaru, harga, nama)
  - Pagination
- **Product Detail**: Halaman detail produk
  - Informasi lengkap produk
  - Gambar produk
  - Stok tersedia
  - Produk terkait
  - Tombol Add to Cart

### 2. 🛒 Shopping Cart
- Melihat semua item di keranjang
- Update quantity produk
- Hapus item dari keranjang
- Clear cart
- Hitung total otomatis
- Cart counter di navigation bar

### 3. 💳 Checkout Process
- Form informasi pengiriman
  - Alamat pengiriman
  - Nomor telepon
  - Catatan pesanan (optional)
- Pilihan metode pembayaran:
  - Credit Card
  - Bank Transfer
  - Cash on Delivery (COD)
- Review pesanan sebelum checkout
- Validasi stock sebelum proses order

### 4. 📦 Order Management
- Daftar semua pesanan user
- Detail pesanan lengkap
  - Informasi produk yang dipesan
  - Status pesanan (pending, processing, completed, cancelled)
  - Status pembayaran (unpaid, paid, failed)
  - Informasi pengiriman
- Cancel order (untuk status pending)

### 5. 💰 Payment Integration (Simulasi)
- Simulasi pembayaran untuk semua metode
- Halaman payment dengan instruksi
- Update status pembayaran otomatis

## Struktur Database

### Tables
1. **categories**: Kategori produk
2. **products**: Data produk
3. **carts**: Item di keranjang belanja
4. **orders**: Data pesanan
5. **order_items**: Detail item pesanan

## Setup Instructions

### 1. Jalankan Migrations
```bash
php artisan migrate
```

### 2. Seed Sample Data
```bash
php artisan db:seed --class=ProductSeeder
```
Atau jalankan semua seeder:
```bash
php artisan db:seed
```

### 3. Storage Link (untuk gambar produk)
```bash
php artisan storage:link
```

## Routes

### Public Routes
- `GET /products` - Daftar produk
- `GET /products/{slug}` - Detail produk

### Authenticated Routes
**Cart:**
- `GET /cart` - Keranjang belanja
- `POST /cart/add/{product}` - Tambah ke keranjang
- `PATCH /cart/{cart}` - Update quantity
- `DELETE /cart/{cart}` - Hapus item
- `DELETE /cart` - Clear cart

**Checkout:**
- `GET /checkout` - Halaman checkout
- `POST /checkout` - Proses checkout
- `GET /checkout/payment/{order}` - Halaman payment
- `POST /checkout/payment/{order}` - Proses payment

**Orders:**
- `GET /orders` - Daftar pesanan
- `GET /orders/{order}` - Detail pesanan
- `POST /orders/{order}/cancel` - Cancel pesanan

## Controllers

1. **ProductController**: Handle product listing dan detail
2. **CartController**: Manage shopping cart
3. **CheckoutController**: Handle checkout dan payment
4. **OrderController**: Manage orders

## Models

1. **Category**: Model kategori dengan relasi ke Product
2. **Product**: Model produk dengan relasi ke Category, Cart, OrderItem
3. **Cart**: Model keranjang dengan relasi ke User dan Product
4. **Order**: Model pesanan dengan relasi ke User dan OrderItem
5. **OrderItem**: Model item pesanan dengan relasi ke Order dan Product

## Views Structure

```
resources/views/
├── products/
│   ├── index.blade.php      # Daftar produk
│   └── show.blade.php        # Detail produk
├── cart/
│   └── index.blade.php       # Keranjang belanja
├── checkout/
│   ├── index.blade.php       # Form checkout
│   └── payment.blade.php     # Halaman payment
└── orders/
    ├── index.blade.php       # Daftar pesanan
    └── show.blade.php        # Detail pesanan
```

## Sample Products

Seeder akan membuat 5 kategori dan 15 produk sample:

### Kategori:
1. Electronics (Headphones, Smart Watch, Mouse)
2. Fashion (T-Shirt, Jeans, Wallet)
3. Home & Living (Coffee Mug, Table Lamp, Cushion)
4. Books (Programming Guide, Business Strategy, Fiction)
5. Sports (Yoga Mat, Dumbbell Set, Water Bottle)

## Testing the System

### 1. Browse Products
- Kunjungi `/products`
- Coba filter dan search
- Klik produk untuk melihat detail

### 2. Add to Cart
- Pada halaman produk, klik "Add to Cart"
- Cart counter di navigation akan update
- Kunjungi `/cart` untuk melihat keranjang

### 3. Checkout
- Di halaman cart, klik "Proceed to Checkout"
- Isi form shipping information
- Pilih payment method
- Klik "Place Order"

### 4. Payment Simulation
- Setelah checkout, akan redirect ke halaman payment
- Klik "Complete Payment (Simulation)"
- Order status akan update ke "processing" dan payment status ke "paid"

### 5. View Orders
- Kunjungi `/orders` untuk melihat semua pesanan
- Klik order untuk melihat detail
- Test cancel order untuk pesanan dengan status pending

## Security Features

- Authentication required untuk cart, checkout, dan orders
- Authorization policy untuk cart dan orders (user hanya bisa akses data mereka sendiri)
- CSRF protection pada semua form
- Stock validation sebelum checkout
- Transaction untuk proses order (rollback jika error)

## Future Enhancements

Fitur yang bisa ditambahkan di masa depan:
1. Admin panel untuk manage products
2. Real payment gateway integration
3. Shipping cost calculation
4. Product reviews dan ratings
5. Wishlist
6. Voucher/discount system
7. Order tracking
8. Email notifications
9. Product variants (size, color)
10. Advanced search dan filters

## Troubleshooting

### Cart tidak update
- Pastikan user sudah login
- Check JavaScript console untuk errors

### Error saat checkout
- Pastikan semua field form sudah diisi dengan benar
- Check stock availability
- Pastikan database connection aktif

### Gambar produk tidak muncul
- Jalankan `php artisan storage:link`
- Check file permissions untuk storage folder

## Support

Untuk pertanyaan atau issue, silakan buka GitHub Issues di repository ini.
