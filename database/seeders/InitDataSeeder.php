<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InitDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'hoten' => 'Hà Lê Đăng Khôi',
            'email' => 'admin@gmail.com',
            'sodienthoai'=>'0123456789',
            'matkhau' => Hash::make('123456'),
            'ngaysinh' => '1995-01-01',
            'role' => 'ADMIN'
        ]);
        User::create([
            'hoten' => 'Staff',
            'email' => 'staff@gmail.com',
            'sodienthoai'=>'0976589541',
            'matkhau' => Hash::make('123456'),
            'ngaysinh' => '2000-01-01',
            'role' => 'STAFF'
        ]);
    }
}
