<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('vi_VN');
        for($i=0; $i<10; $i++){
            DB::table('users')->insert([
                'id' => Str::uuid(),
                'name'=>$faker->name(),
                'email'=>$faker->unique()->safeEmail(),
                'phone' => '0'.$faker->numberBetween(100000000,999999999),
                'birthday' => $faker->date(),
                'password' => Hash::make('12345678'),
            ]);
        }
    }
}
