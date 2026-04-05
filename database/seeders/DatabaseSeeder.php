<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Xóa dữ liệu cũ theo thứ tự để tránh lỗi khóa ngoại
        DB::table('order_items')->delete();
        DB::table('orders')->delete();
        DB::table('inventories')->delete();
        DB::table('products')->delete();
        DB::table('categories')->delete();
        DB::table('users')->delete();

        // 2. Seed Users 
        DB::table('users')->insert([
            [
                'id' => 1,
                'fullname' => 'Admin',            
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password'), 
                'role' => 'admin',
                'created_at' => now(),
            ],
            [
                'id' => 2,
                'fullname' => 'Customer',      
                'email' => 'customer@gmail.com',
                'password' => Hash::make('password'), 
                'role' => 'customer',
                'created_at' => now(),
            ],
            [
                'id' => 3,
                'fullname' => 'Hung',      
                'email' => 'hung@gmail.com',
                'password' => Hash::make('123456'), 
                'role' => 'customer',
                'created_at' => now(),
            ]
        ]);

        // 3. Seed Categories (Chỉ các danh mục liên quan công nghệ)
        $categories = [
            ['id' => 1, 'name' => 'iPhone', 'slug' => 'iphone'],
            ['id' => 2, 'name' => 'Samsung', 'slug' => 'samsung'],
            ['id' => 3, 'name' => 'Xiaomi', 'slug' => 'xiaomi'],
            ['id' => 4, 'name' => 'Oppo', 'slug' => 'oppo'],
            ['id' => 5, 'name' => 'Redmi', 'slug' => 'redmi'],
            ['id' => 6, 'name' => 'Poco', 'slug' => 'poco'],
            ['id' => 7, 'name' => 'Phụ Kiện', 'slug' => 'phu-kien'],
            ['id' => 8, 'name' => 'Sạc & Cáp', 'slug' => 'sac-cap'],
            ['id' => 9, 'name' => 'Tai Nghe', 'slug' => 'tai-nghe'],
        ];
        foreach ($categories as $cat) {
            DB::table('categories')->insert(array_merge($cat, ['created_at' => now(), 'updated_at' => now()]));
        }

        // 4. Seed Products (Chỉ các sản phẩm công nghệ - 26 sản phẩm)
        $products = [
            // iPhone
            ['id' => 1, 'categoryid' => 1, 'name' => 'iPhone 15 Pro Max', 'price' => 29990000, 'saleprice' => 27990000, 'IsOnSale' => 1, 'imageUrl' => '/images/products/iphone15pro.webp'],
            ['id' => 2, 'categoryid' => 1, 'name' => 'iPhone 14 Plus', 'price' => 24990000, 'saleprice' => null, 'IsOnSale' => 0, 'imageUrl' => '/images/products/iphone14plus.webp'],
            
            // Samsung
            ['id' => 3, 'categoryid' => 2, 'name' => 'Samsung Galaxy S24', 'price' => 34990000, 'saleprice' => 31990000, 'IsOnSale' => 1, 'imageUrl' => '/images/products/s24.webp'],
            ['id' => 4, 'categoryid' => 2, 'name' => 'Samsung Galaxy A50', 'price' => 10990000, 'saleprice' => null, 'IsOnSale' => 0, 'imageUrl' => '/images/products/18.jpg'],
            
            // Xiaomi
            ['id' => 5, 'categoryid' => 3, 'name' => 'Xiaomi 14 Ultra', 'price' => 49990000, 'saleprice' => 45990000, 'IsOnSale' => 1, 'imageUrl' => '/images/products/xiaomi-14-ultra.webp'],
            ['id' => 6, 'categoryid' => 3, 'name' => 'Xiaomi 13T Pro', 'price' => 39990000, 'saleprice' => 37990000, 'IsOnSale' => 1, 'imageUrl' => '/images/products/xiaomi13t.jpg'],
            ['id' => 7, 'categoryid' => 3, 'name' => 'Xiaomi 14', 'price' => 32990000, 'saleprice' => null, 'IsOnSale' => 0, 'imageUrl' => '/images/products/xiaomi14.jpg'],
            
            // Oppo
            ['id' => 8, 'categoryid' => 4, 'name' => 'Oppo Reno 11', 'price' => 28990000, 'saleprice' => 26990000, 'IsOnSale' => 1, 'imageUrl' => '/images/products/op15.jpg'],
            ['id' => 9, 'categoryid' => 4, 'name' => 'Oppo A18', 'price' => 8990000, 'saleprice' => null, 'IsOnSale' => 0, 'imageUrl' => '/images/products/9.jpg'],
            
            // Redmi
            ['id' => 10, 'categoryid' => 5, 'name' => 'Redmi Note 13', 'price' => 14990000, 'saleprice' => 12990000, 'IsOnSale' => 1, 'imageUrl' => '/images/products/redmi13.jpg'],
            ['id' => 11, 'categoryid' => 5, 'name' => 'Redmi 13', 'price' => 11990000, 'saleprice' => null, 'IsOnSale' => 0, 'imageUrl' => '/images/products/10.jpg'],
            
            // Poco
            ['id' => 12, 'categoryid' => 6, 'name' => 'Poco M5', 'price' => 19990000, 'saleprice' => 17990000, 'IsOnSale' => 1, 'imageUrl' => '/images/products/poco-m5.webp'],
            ['id' => 13, 'categoryid' => 6, 'name' => 'Poco X6 Pro', 'price' => 25990000, 'saleprice' => null, 'IsOnSale' => 0, 'imageUrl' => '/images/products/poco.jpg'],
            
            // Phụ Kiện - Sạc
            ['id' => 14, 'categoryid' => 8, 'name' => 'Sạc Nhanh 20W Type-C', 'price' => 399000, 'saleprice' => 299000, 'IsOnSale' => 1, 'imageUrl' => '/images/products/sac20w.jpg'],
            ['id' => 15, 'categoryid' => 8, 'name' => 'Sạc Nhanh 65W', 'price' => 899000, 'saleprice' => null, 'IsOnSale' => 0, 'imageUrl' => '/images/products/23.jpg'],
            ['id' => 16, 'categoryid' => 8, 'name' => 'Cáp USB-C Chất Lượng', 'price' => 199000, 'saleprice' => 149000, 'IsOnSale' => 1, 'imageUrl' => '/images/products/24.jpg'],
            
            // Phụ Kiện - Power Bank
            ['id' => 17, 'categoryid' => 7, 'name' => 'Sạc Dự Phòng 20000mAh', 'price' => 1999000, 'saleprice' => 1799000, 'IsOnSale' => 1, 'imageUrl' => '/images/products/sac-du-phong.webp'],
            ['id' => 18, 'categoryid' => 7, 'name' => 'Sạc Dự Phòng 10000mAh', 'price' => 999000, 'saleprice' => null, 'IsOnSale' => 0, 'imageUrl' => '/images/products/11.jpg'],
            
            // Tai Nghe
            ['id' => 19, 'categoryid' => 9, 'name' => 'Tai Nghe Không Dây', 'price' => 999000, 'saleprice' => 890000, 'IsOnSale' => 1, 'imageUrl' => '/images/products/tai-nghe.webp'],
            ['id' => 20, 'categoryid' => 9, 'name' => 'Tai Nghe Chui Cao Cấp', 'price' => 599000, 'saleprice' => null, 'IsOnSale' => 0, 'imageUrl' => '/images/products/25.jpg'],
            
            // Các sản phẩm bổ sung khác
            ['id' => 26, 'categoryid' => 2, 'name' => 'Samsung Galaxy M35', 'price' => 9990000, 'saleprice' => null, 'IsOnSale' => 0, 'imageUrl' => '/images/products/13.jpg'],
            ['id' => 27, 'categoryid' => 7, 'name' => 'Ốp Điện Thoại Nhựa', 'price' => 199000, 'saleprice' => 99000, 'IsOnSale' => 1, 'imageUrl' => '/images/products/14.jpg'],
            ['id' => 28, 'categoryid' => 9, 'name' => 'Tai Nghe Gaming RGB', 'price' => 1490000, 'saleprice' => 1190000, 'IsOnSale' => 1, 'imageUrl' => '/images/products/16.jpg'],
            ['id' => 29, 'categoryid' => 8, 'name' => 'Sạc Nhanh 30W PD', 'price' => 599000, 'saleprice' => 499000, 'IsOnSale' => 1, 'imageUrl' => '/images/products/17.jpg'],
            ['id' => 30, 'categoryid' => 3, 'name' => 'Xiaomi Mix 5', 'price' => 41990000, 'saleprice' => null, 'IsOnSale' => 0, 'imageUrl' => '/images/products/19.jpg'],
            ['id' => 33, 'categoryid' => 7, 'name' => 'Kính Cường Lực 9H', 'price' => 299000, 'saleprice' => 199000, 'IsOnSale' => 1, 'imageUrl' => '/images/products/27.jpg'],
        ];
        foreach ($products as $prod) {
            DB::table('products')->insert(array_merge($prod, ['created_at' => now(), 'updated_at' => now()]));
        }

        // 5. Seed Inventories
        $productIds = DB::table('products')->pluck('id')->toArray(); 
        foreach ($productIds as $id) {
            DB::table('inventories')->insert([
                'ProductId' => $id,
                'QuantityInStock' => rand(10, 100),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 6. Seed Orders mẫu (Mở rộng lên 15 đơn hàng đa dạng)
        $orders = [
            ['id' => 1, 'userid' => 2, 'status' => 'pending', 'paymentstatus' => 'unpaid', 'totalprice' => 31990000, 'shipname' => 'Nguyen Van A', 'shipaddress' => '123 Le Loi, Bình Chánh, TP.HCM', 'shipphone' => '0901234567', 'orderdate' => now()],
            ['id' => 2, 'userid' => 2, 'status' => 'delivered', 'paymentstatus' => 'paid', 'totalprice' => 29990000, 'shipname' => 'Nguyen Van A', 'shipaddress' => 'KDC Trung Sơn, Bình Chánh', 'shipphone' => '0901234567', 'orderdate' => now()->subDays(5)],
            ['id' => 3, 'userid' => 2, 'status' => 'processing', 'paymentstatus' => 'paid', 'totalprice' => 1200000, 'shipname' => 'Nguyen Van A', 'shipaddress' => 'Chợ Bình Chánh, TP.HCM', 'shipphone' => '0901234567', 'orderdate' => now()->subHours(10)],
            ['id' => 4, 'userid' => 1, 'status' => 'cancelled', 'paymentstatus' => 'unpaid', 'totalprice' => 3490000, 'shipname' => 'Admin', 'shipaddress' => 'Tân Phú, Đồng Nai', 'shipphone' => '0988888888', 'orderdate' => now()->subDays(10)],
            ['id' => 5, 'userid' => 2, 'status' => 'delivered', 'paymentstatus' => 'paid', 'totalprice' => 45990000, 'shipname' => 'Tran Thi B', 'shipaddress' => '456 Nguyen Hue, Q1, TP.HCM', 'shipphone' => '0912345678', 'orderdate' => now()->subDays(3)],
            ['id' => 6, 'userid' => 2, 'status' => 'processing', 'paymentstatus' => 'paid', 'totalprice' => 12990000, 'shipname' => 'Le Van C', 'shipaddress' => '789 Tran Hung Dao, Q5, TP.HCM', 'shipphone' => '0923456789', 'orderdate' => now()->subHours(5)],
            ['id' => 7, 'userid' => 2, 'status' => 'pending', 'paymentstatus' => 'unpaid', 'totalprice' => 26990000, 'shipname' => 'Pham Minh D', 'shipaddress' => '321 Ngo Gia Tu, Q10, TP.HCM', 'shipphone' => '0934567890', 'orderdate' => now()->subHours(2)],
            ['id' => 8, 'userid' => 2, 'status' => 'delivered', 'paymentstatus' => 'paid', 'totalprice' => 37990000, 'shipname' => 'Hoang Thu E', 'shipaddress' => '654 Ba Thang Hai, Q10, TP.HCM', 'shipphone' => '0945678901', 'orderdate' => now()->subDays(7)],
            ['id' => 9, 'userid' => 2, 'status' => 'processing', 'paymentstatus' => 'paid', 'totalprice' => 2090000, 'shipname' => 'Dang Quoc F', 'shipaddress' => '999 Cach Mang Thang Tam, Q3, TP.HCM', 'shipphone' => '0956789012', 'orderdate' => now()->subDays(1)],
            ['id' => 10, 'userid' => 2, 'status' => 'delivered', 'paymentstatus' => 'paid', 'totalprice' => 1890000, 'shipname' => 'Vu Thị G', 'shipaddress' => '111 Vo Van Tan, Q3, TP.HCM', 'shipphone' => '0967890123', 'orderdate' => now()->subDays(12)],
            ['id' => 11, 'userid' => 2, 'status' => 'cancelled', 'paymentstatus' => 'unpaid', 'totalprice' => 8990000, 'shipname' => 'Bui Van H', 'shipaddress' => '222 Dien Bien Phu, Q1, TP.HCM', 'shipphone' => '0978901234', 'orderdate' => now()->subDays(15)],
            ['id' => 12, 'userid' => 2, 'status' => 'pending', 'paymentstatus' => 'paid', 'totalprice' => 49890000, 'shipname' => 'Ngo Hieu I', 'shipaddress' => '333 Tran Quang Khai, Q1, TP.HCM', 'shipphone' => '0989012345', 'orderdate' => now()->subHours(1)],
            ['id' => 13, 'userid' => 2, 'status' => 'processing', 'paymentstatus' => 'paid', 'totalprice' => 32990000, 'shipname' => 'Trinh Van J', 'shipaddress' => '444 Ly Tu Trong, Q1, TP.HCM', 'shipphone' => '0990123456', 'orderdate' => now()->subDays(2)],
            ['id' => 14, 'userid' => 2, 'status' => 'delivered', 'paymentstatus' => 'paid', 'totalprice' => 17990000, 'shipname' => 'Duong Thi K', 'shipaddress' => '555 Pasteur, Q1, TP.HCM', 'shipphone' => '0901234567', 'orderdate' => now()->subDays(6)],
            ['id' => 15, 'userid' => 2, 'status' => 'pending', 'paymentstatus' => 'unpaid', 'totalprice' => 999000, 'shipname' => 'Nguyen Hoa L', 'shipaddress' => '666 Nguyen Thai Hoc, Q1, TP.HCM', 'shipphone' => '0912345678', 'orderdate' => now()->subHours(3)],
        ];

        foreach ($orders as $order) {
            DB::table('orders')->insert(array_merge($order, ['created_at' => now()]));
        }

        // 7. Seed OrderItems mẫu (Gắn sản phẩm tương ứng cho 15 đơn hàng)
        DB::table('order_items')->insert([
            // Order 1: iPhone 15 Pro Max
            ['orderid' => 1, 'productid' => 1, 'quantity' => 1, 'unitprice' => 31990000, 'created_at' => now()],
            // Order 2: Samsung S24
            ['orderid' => 2, 'productid' => 3, 'quantity' => 1, 'unitprice' => 29990000, 'created_at' => now()],
            // Order 3: Xiaomi 14 + Power Bank
            ['orderid' => 3, 'productid' => 5, 'quantity' => 1, 'unitprice' => 45990000, 'created_at' => now()],
            ['orderid' => 3, 'productid' => 17, 'quantity' => 1, 'unitprice' => 1799000, 'created_at' => now()],
            // Order 4: Samsung Galaxy A50
            ['orderid' => 4, 'productid' => 4, 'quantity' => 1, 'unitprice' => 10990000, 'created_at' => now()],
            // Order 5: Xiaomi 14 Ultra
            ['orderid' => 5, 'productid' => 5, 'quantity' => 1, 'unitprice' => 45990000, 'created_at' => now()],
            // Order 6: Redmi Note 13
            ['orderid' => 6, 'productid' => 10, 'quantity' => 1, 'unitprice' => 12990000, 'created_at' => now()],
            // Order 7: Oppo Reno 11
            ['orderid' => 7, 'productid' => 8, 'quantity' => 1, 'unitprice' => 26990000, 'created_at' => now()],
            // Order 8: Xiaomi 13T Pro
            ['orderid' => 8, 'productid' => 6, 'quantity' => 1, 'unitprice' => 37990000, 'created_at' => now()],
            // Order 9: Sạc Nhanh + Kính Cường Lực
            ['orderid' => 9, 'productid' => 14, 'quantity' => 1, 'unitprice' => 299000, 'created_at' => now()],
            ['orderid' => 9, 'productid' => 33, 'quantity' => 1, 'unitprice' => 199000, 'created_at' => now()],
            // Order 10: Sạc Dự Phòng + Tai Nghe
            ['orderid' => 10, 'productid' => 17, 'quantity' => 1, 'unitprice' => 1799000, 'created_at' => now()],
            ['orderid' => 10, 'productid' => 19, 'quantity' => 1, 'unitprice' => 890000, 'created_at' => now()],
            // Order 11: Oppo A18
            ['orderid' => 11, 'productid' => 9, 'quantity' => 1, 'unitprice' => 8990000, 'created_at' => now()],
            // Order 12: Xiaomi 14 Ultra + Sạc 65W
            ['orderid' => 12, 'productid' => 5, 'quantity' => 1, 'unitprice' => 45990000, 'created_at' => now()],
            ['orderid' => 12, 'productid' => 15, 'quantity' => 1, 'unitprice' => 899000, 'created_at' => now()],
            // Order 13: Samsung S24 + Sạc Nhanh
            ['orderid' => 13, 'productid' => 3, 'quantity' => 1, 'unitprice' => 31990000, 'created_at' => now()],
            ['orderid' => 13, 'productid' => 14, 'quantity' => 1, 'unitprice' => 299000, 'created_at' => now()],
            // Order 14: Poco M5 + Tai Nghe Gaming
            ['orderid' => 14, 'productid' => 12, 'quantity' => 1, 'unitprice' => 17990000, 'created_at' => now()],
            // Order 15: Tai Nghe Chui
            ['orderid' => 15, 'productid' => 20, 'quantity' => 1, 'unitprice' => 599000, 'created_at' => now()],
        ]);
        // Reset auto_increment counters to avoid duplicate key errors
        $this->resetAutoIncrement();
    }

    /**
     * Reset auto_increment sequences after seeding
     * Prevents duplicate key errors when creating new records
     */
    private function resetAutoIncrement()
    {
        $tables = ['users', 'categories', 'products', 'inventories', 'orders', 'order_items'];
        
        foreach ($tables as $table) {
            $maxId = DB::table($table)->max('id') ?? 0;
            
            // MySQL
            if (DB::connection()->getDriverName() === 'mysql') {
                DB::statement("ALTER TABLE {$table} AUTO_INCREMENT = " . ($maxId + 1));
            }
            // PostgreSQL
            elseif (DB::connection()->getDriverName() === 'pgsql') {
                $sequence = "{$table}_id_seq";
                DB::statement("ALTER SEQUENCE {$sequence} RESTART WITH " . ($maxId + 1));
            }
            // SQLite (no action needed, uses ROWID)
        }
    }
}
