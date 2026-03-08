<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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

        // 2. Dữ liệu Users (Nhập liệu theo yêu cầu bài kiểm tra)
        $users = [
            [
                'fullname' => 'Nguyễn Văn Admin',
                'email' => 'admin@theking.com',
                'password' => Hash::make('12345678'),
                'role' => 'Admin',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'fullname' => 'Hồ Tấn Đạt',
                'email' => 'hotandat@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'Customer',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'fullname' => 'Trần Thị B',
                'email' => 'tranthib@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'Customer',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'fullname' => 'Lê Đại Ca',
                'email' => 'ledaica@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'Customer',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'fullname' => 'Phạm Phương D',
                'email' => 'phamphuongd@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'Customer',
                'created_at' => now(), 'updated_at' => now()
            ]
        ];

        DB::table('users')->insert($users);
    }
}
