<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;
class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        for ($i = 21; $i <= 120; $i++) {
            Customer::create([
                'customer_name' => 'Customer ' . $i,
                'email' => 'customer' . $i . '@example.com',
                'tel_num' => '0123456789' . $i,
                'address' => 'Address ' . $i,
                'is_active' => rand(0, 1) //
            ]);
        }
    }
}
