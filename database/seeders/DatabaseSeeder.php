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
                'role' => 'user',
                'created_at' => now(),
            ]
        ]);

        // 3. Seed Categories (Theo file nhiệm vụ)
        $categories = [
            ['id' => 1, 'name' => 'iPhone', 'slug' => 'iphone'],
            ['id' => 2, 'name' => 'Samsung', 'slug' => 'samsung'],
            ['id' => 3, 'name' => 'Phụ kiện', 'slug' => 'phu-kien'],
            ['id' => 4, 'name' => 'Xiaomi', 'slug' => 'xiaomi'],
        ];
        foreach ($categories as $cat) {
            DB::table('categories')->insert(array_merge($cat, ['created_at' => now(), 'updated_at' => now()]));
        }

        // 4. Seed Products
        $products = [
            ['id' => 1, 'categoryid' => 1, 'name' => 'iPhone 15 Pro Max', 'price' => 34990000, 'saleprice' => 31990000, 'IsOnSale' => 1, 'imageUrl' => 'iphone15pro.webp'],
            ['id' => 2, 'categoryid' => 1, 'name' => 'iPhone 14 Plus', 'price' => 22990000, 'saleprice' => null, 'IsOnSale' => 0, 'imageUrl' => 'iphone14plus.webp'],
            ['id' => 3, 'categoryid' => 2, 'name' => 'Samsung Galaxy S24 Ultra', 'price' => 33990000, 'saleprice' => 29990000, 'IsOnSale' => 1, 'imageUrl' => 's24.webp'],
            ['id' => 4, 'categoryid' => 4, 'name' => 'POCO M5 Edition', 'price' => 3990000, 'saleprice' => 3490000, 'IsOnSale' => 1, 'imageUrl' => 'poco-m5.webp'],
            ['id' => 5, 'categoryid' => 4, 'name' => 'Xiaomi 14 Ultra', 'price' => 32000000, 'saleprice' => null, 'IsOnSale' => 0, 'imageUrl' => 'xiaomi-14-ultra.webp'],
            ['id' => 6, 'categoryid' => 3, 'name' => 'Sạc dự phòng 20.000mAh', 'price' => 850000, 'saleprice' => 690000, 'IsOnSale' => 1, 'imageUrl' => 'sac-du-phong.webp'],
            ['id' => 7, 'categoryid' => 3, 'name' => 'Tai nghe Bluetooth True Wireless', 'price' => 1200000, 'saleprice' => null, 'IsOnSale' => 0, 'imageUrl' => 'tai-nghe.webp'],
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

        // 6. Seed Orders mẫu (Đã mở rộng lên 4 đơn hàng)
        $orders = [
            [
                'id' => 1,
                'userid' => 2,
                'status' => 'Pending',
                'paymentstatus' => 'Unpaid',
                'totalprice' => 31990000,
                'shipname' => 'Nguyen Van A',
                'shipaddress' => '123 Le Loi, Bình Chánh, TP.HCM',
                'shipphone' => '0901234567',
                'orderdate' => now(),
            ],
            [
                'id' => 2,
                'userid' => 2,
                'status' => 'Delivered',
                'paymentstatus' => 'Paid',
                'totalprice' => 29990000,
                'shipname' => 'Nguyen Van A',
                'shipaddress' => 'KDC Trung Sơn, Bình Chánh',
                'shipphone' => '0901234567',
                'orderdate' => now()->subDays(5),
            ],
            [
                'id' => 3,
                'userid' => 2,
                'status' => 'Processing',
                'paymentstatus' => 'Paid',
                'totalprice' => 1200000,
                'shipname' => 'Nguyen Van A',
                'shipaddress' => 'Chợ Bình Chánh, TP.HCM',
                'shipphone' => '0901234567',
                'orderdate' => now()->subHours(10),
            ],
            [
                'id' => 4,
                'userid' => 1,
                'status' => 'Cancelled',
                'paymentstatus' => 'Unpaid',
                'totalprice' => 3490000,
                'shipname' => 'Admin',
                'shipaddress' => 'Tân Phú, Đồng Nai',
                'shipphone' => '0988888888',
                'orderdate' => now()->subDays(10),
            ]
        ];

        foreach ($orders as $order) {
            DB::table('orders')->insert(array_merge($order, ['created_at' => now()]));
        }

        // 7. Seed OrderItems mẫu (Gắn sản phẩm tương ứng cho 4 đơn hàng trên)
        DB::table('order_items')->insert([
            ['orderid' => 1, 'productid' => 1, 'quantity' => 1, 'unitprice' => 31990000, 'created_at' => now()],
            ['orderid' => 2, 'productid' => 3, 'quantity' => 1, 'unitprice' => 29990000, 'created_at' => now()],
            ['orderid' => 3, 'productid' => 7, 'quantity' => 1, 'unitprice' => 1200000, 'created_at' => now()],
            ['orderid' => 4, 'productid' => 4, 'quantity' => 1, 'unitprice' => 3490000, 'created_at' => now()],
        ]);
    }
}
