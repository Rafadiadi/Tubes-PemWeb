<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Categories
        $categories = [
            [
                'name' => 'Vitamin & Suplemen',
                'slug' => 'vitamin-suplemen',
                'description' => 'Vitamin dan suplemen untuk kesehatan tubuh',
            ],
            [
                'name' => 'Obat-obatan',
                'slug' => 'obat-obatan',
                'description' => 'Obat-obatan dan produk farmasi',
            ],
            [
                'name' => 'Alat Kesehatan',
                'slug' => 'alat-kesehatan',
                'description' => 'Alat kesehatan dan medical devices',
            ],
            [
                'name' => 'Perawatan Kulit',
                'slug' => 'perawatan-kulit',
                'description' => 'Produk perawatan kulit dan kecantikan',
            ],
            [
                'name' => 'Kesehatan Ibu & Anak',
                'slug' => 'kesehatan-ibu-anak',
                'description' => 'Produk kesehatan untuk ibu dan anak',
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }

        // Create Products
        $products = [
            // Vitamin & Suplemen
            [
                'category_id' => 1,
                'name' => 'Vitamin C 1000mg',
                'slug' => 'vitamin-c-1000mg',
                'description' => 'Suplemen vitamin C dosis tinggi untuk meningkatkan daya tahan tubuh. Membantu melindungi sel dari kerusakan oksidatif dan mendukung sistem kekebalan tubuh. Cocok untuk konsumsi harian.',
                'price' => 85000,
                'stock' => 150,
                'is_active' => true,
            ],
            [
                'category_id' => 1,
                'name' => 'Multivitamin Complete',
                'slug' => 'multivitamin-complete',
                'description' => 'Multivitamin lengkap dengan mineral dan antioksidan. Mengandung 13 vitamin dan 9 mineral penting untuk memenuhi kebutuhan nutrisi harian. Cocok untuk segala usia.',
                'price' => 125000,
                'stock' => 100,
                'is_active' => true,
            ],
            [
                'category_id' => 1,
                'name' => 'Omega-3 Fish Oil',
                'slug' => 'omega-3-fish-oil',
                'description' => 'Suplemen minyak ikan omega-3 untuk kesehatan jantung dan otak. Mengandung EPA dan DHA tinggi. Membantu menurunkan kolesterol dan trigliserida.',
                'price' => 175000,
                'stock' => 80,
                'is_active' => true,
            ],

            // Obat-obatan
            [
                'category_id' => 2,
                'name' => 'Paracetamol 500mg',
                'slug' => 'paracetamol-500mg',
                'description' => 'Obat pereda nyeri dan penurun demam. Efektif untuk mengatasi sakit kepala, nyeri ringan hingga sedang, dan demam. Strip isi 10 tablet.',
                'price' => 8000,
                'stock' => 500,
                'is_active' => true,
            ],
            [
                'category_id' => 2,
                'name' => 'Antasida Tablet',
                'slug' => 'antasida-tablet',
                'description' => 'Obat untuk mengatasi masalah lambung dan maag. Menetralkan asam lambung berlebih dan meredakan nyeri ulu hati. Rasa mint yang menyegarkan.',
                'price' => 12000,
                'stock' => 300,
                'is_active' => true,
            ],
            [
                'category_id' => 2,
                'name' => 'Obat Batuk Herbal',
                'slug' => 'obat-batuk-herbal',
                'description' => 'Sirup obat batuk dari bahan alami. Meredakan batuk berdahak dan batuk kering. Mengandung madu, jahe, dan ekstrak herbal alami. Aman untuk dewasa dan anak di atas 6 tahun.',
                'price' => 35000,
                'stock' => 200,
                'is_active' => true,
            ],

            // Alat Kesehatan
            [
                'category_id' => 3,
                'name' => 'Termometer Digital',
                'slug' => 'termometer-digital',
                'description' => 'Termometer digital dengan pembacaan cepat dan akurat. Hasil dalam 10 detik. Dilengkapi dengan memori pembacaan terakhir dan alarm demam. Waterproof dan mudah dibersihkan.',
                'price' => 45000,
                'stock' => 120,
                'is_active' => true,
            ],
            [
                'category_id' => 3,
                'name' => 'Tensimeter Digital',
                'slug' => 'tensimeter-digital',
                'description' => 'Alat ukur tekanan darah digital otomatis. Layar LCD besar, mudah dibaca. Deteksi detak jantung tidak teratur. Memori untuk 60 hasil pengukuran. Cocok untuk penggunaan rumah.',
                'price' => 285000,
                'stock' => 50,
                'is_active' => true,
            ],
            [
                'category_id' => 3,
                'name' => 'Masker Medis 3 Ply',
                'slug' => 'masker-medis-3-ply',
                'description' => 'Masker medis 3 lapis sekali pakai. Standar medis dengan filtrasi bakteri >95%. Earloop elastis yang nyaman. Box isi 50 pcs. Cocok untuk perlindungan sehari-hari.',
                'price' => 65000,
                'stock' => 250,
                'is_active' => true,
            ],

            // Perawatan Kulit
            [
                'category_id' => 4,
                'name' => 'Sunscreen SPF 50+',
                'slug' => 'sunscreen-spf-50',
                'description' => 'Tabir surya dengan perlindungan tinggi dari sinar UVA dan UVB. Formula ringan, tidak lengket, dan tahan air. Cocok untuk semua jenis kulit. Melindungi dari penuaan dini dan kanker kulit.',
                'price' => 95000,
                'stock' => 180,
                'is_active' => true,
            ],
            [
                'category_id' => 4,
                'name' => 'Moisturizer Hyaluronic Acid',
                'slug' => 'moisturizer-hyaluronic-acid',
                'description' => 'Pelembab wajah dengan kandungan hyaluronic acid untuk hidrasi maksimal. Membantu menjaga kelembaban kulit, mengurangi garis halus, dan membuat kulit lebih kenyal. Non-comedogenic.',
                'price' => 145000,
                'stock' => 150,
                'is_active' => true,
            ],
            [
                'category_id' => 4,
                'name' => 'Face Wash Acne Care',
                'slug' => 'face-wash-acne-care',
                'description' => 'Sabun cuci muka untuk kulit berjerawat. Mengandung salicylic acid dan tea tree oil. Membersihkan pori-pori, mengurangi minyak berlebih, dan mencegah jerawat. pH balanced.',
                'price' => 55000,
                'stock' => 220,
                'is_active' => true,
            ],

            // Kesehatan Ibu & Anak
            [
                'category_id' => 5,
                'name' => 'Susu Formula Bayi 0-6 Bulan',
                'slug' => 'susu-formula-bayi-0-6-bulan',
                'description' => 'Susu formula untuk bayi usia 0-6 bulan. Diperkaya dengan DHA, ARA, dan prebiotik untuk mendukung perkembangan otak dan sistem pencernaan. Mendekati komposisi ASI.',
                'price' => 165000,
                'stock' => 100,
                'is_active' => true,
            ],
            [
                'category_id' => 5,
                'name' => 'Vitamin Prenatal',
                'slug' => 'vitamin-prenatal',
                'description' => 'Vitamin lengkap untuk ibu hamil dan menyusui. Mengandung asam folat, zat besi, kalsium, dan DHA. Mendukung kesehatan ibu dan perkembangan janin yang optimal.',
                'price' => 135000,
                'stock' => 90,
                'is_active' => true,
            ],
            [
                'category_id' => 5,
                'name' => 'Baby Thermometer',
                'slug' => 'baby-thermometer',
                'description' => 'Termometer khusus bayi dengan teknologi inframerah. Pengukuran tanpa sentuh di dahi, cepat dan akurat. Layar LED dengan indikator warna untuk demam. Mode silent untuk bayi tidur.',
                'price' => 195000,
                'stock' => 75,
                'is_active' => true,
            ],
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }
    }
}
