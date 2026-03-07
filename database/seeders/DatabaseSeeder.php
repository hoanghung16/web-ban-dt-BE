<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 1. Xóa toàn bộ dữ liệu (Theo thứ tự khóa ngoại để tránh lỗi)
        DB::table('order_items')->delete();
        DB::table('orders')->delete();
        DB::table('inventories')->delete();
        DB::table('products')->delete();
        DB::table('users')->delete();
        DB::table('categories')->delete();

        // 2. Dữ liệu Users
        $adminId = DB::table('users')->insertGetId([
            'fullname' => 'Quản Trị Viên',
            'email' => 'admin@theking.com',
            'password' => \Illuminate\Support\Facades\Hash::make('12345678'),
            'role' => 'Admin',
            'created_at' => now(), 'updated_at' => now()
        ]);
        $customerId = DB::table('users')->insertGetId([
            'fullname' => 'Nguyễn Khách Hàng',
            'email' => 'khachhang@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('12345678'),
            'role' => 'Customer',
            'created_at' => now(), 'updated_at' => now()
        ]);

        // 3. Dữ liệu Categories
        $catPhones = DB::table('categories')->insertGetId([
            'name' => 'Điện thoại thông minh',
            'slug' => 'dien-thoai-thong-minh',
            'created_at' => now(), 'updated_at' => now()
        ]);
        $catAccessories = DB::table('categories')->insertGetId([
            'name' => 'Phụ kiện',
            'slug' => 'phu-kien',
            'created_at' => now(), 'updated_at' => now()
        ]);

        // 4. Dữ liệu Products
        $productsData = [
            ['categoryid' => $catPhones, 'name' => 'iPhone 15 Pro Max', 'price' => 29990000, 'IsOnSale' => 0, 'imageUrl' => '/1.jpg'],
            ['categoryid' => $catPhones, 'name' => 'Xiaomi 14 Ultra', 'price' => 31990000, 'IsOnSale' => 1, 'imageUrl' => '/xiaomi14.jpg'],
            ['categoryid' => $catPhones, 'name' => 'Poco X6 Pro 5G', 'price' => 8490000, 'IsOnSale' => 0, 'imageUrl' => '/poco.jpg'],
            ['categoryid' => $catPhones, 'name' => 'Redmi Note 13', 'price' => 4890000, 'IsOnSale' => 0, 'imageUrl' => '/redmi13.jpg'],
            ['categoryid' => $catAccessories, 'name' => 'Ốp lưng iPhone 15', 'price' => 250000, 'IsOnSale' => 0, 'imageUrl' => '/op15.jpg'],
            ['categoryid' => $catAccessories, 'name' => 'Sạc Nhanh 20W', 'price' => 350000, 'IsOnSale' => 1, 'imageUrl' => '/sac20w.jpg'],
        ];

        $productIds = [];
        foreach ($productsData as $p) {
            $p['created_at'] = now();
            $p['updated_at'] = now();
            $productIds[] = DB::table('products')->insertGetId($p);
        }

        // 5. Dữ liệu Kho hàng (Inventories)
        foreach ($productIds as $pid) {
            DB::table('inventories')->insert([
                'ProductId' => $pid,
                'QuantityInStock' => rand(10, 100), // Random số lượng từ 10 - 100
                'created_at' => now(), 'updated_at' => now()
            ]);
        }

        // 6. Dữ liệu Đơn hàng (Orders)
        $order1Id = DB::table('orders')->insertGetId([
            'userid' => $customerId,
            'orderdate' => now()->subDays(2),
            'status' => 'Đã giao',
            'paymentstatus' => 'Đã thanh toán',
            'totalprice' => 30340000, // 29990000 + 350000
            'shipname' => 'Nguyễn Khách Hàng',
            'shipaddress' => '123 Đường Điện Biên Phủ, TP.HCM',
            'shipphone' => '0987654321',
            'created_at' => now(), 'updated_at' => now()
        ]);

        $order2Id = DB::table('orders')->insertGetId([
            'userid' => $adminId, // Giả sử Admin mua test
            'orderdate' => now(),
            'status' => 'Chờ xử lý',
            'paymentstatus' => 'Chưa thanh toán',
            'totalprice' => 8490000,
            'shipname' => 'Quản Trị Viên',
            'shipaddress' => 'Tại Cửa Hàng The King Mobile',
            'shipphone' => '0912345678',
            'created_at' => now(), 'updated_at' => now()
        ]);

        // 7. Dữ liệu Chi tiết Đơn hàng (OrderItems) - Nối Product vào Order
        // Đơn 1: Mua iPhone 15 Promax và Sạc 20W
        DB::table('order_items')->insert([
            ['orderid' => $order1Id, 'productid' => $productIds[0], 'quantity' => 1, 'unitprice' => 29990000, 'created_at' => now(), 'updated_at' => now()],
            ['orderid' => $order1Id, 'productid' => $productIds[5], 'quantity' => 1, 'unitprice' => 350000, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Đơn 2: Mua Poco
        DB::table('order_items')->insert([
            ['orderid' => $order2Id, 'productid' => $productIds[2], 'quantity' => 1, 'unitprice' => 8490000, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
