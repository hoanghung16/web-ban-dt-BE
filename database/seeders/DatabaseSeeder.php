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
        // DB::table('order_items')->delete();
        // DB::table('orders')->delete();
        // DB::table('inventories')->delete();
        // DB::table('products')->delete();
        DB::table('users')->delete();
        // DB::table('categories')->delete();

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
    }
}
