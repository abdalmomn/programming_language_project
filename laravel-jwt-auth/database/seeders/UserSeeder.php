<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        User::create([
        'name' => 'pharma ',
        'phone' => '0950513115' ,
        'password' => Hash::make('12345678'),
        'role' => 'admin'
        
        ]);
        User::create([
        'name' => 'afaq ',
        'phone' => '0953230952' ,
        'password' => Hash::make('12345678'),
        'role' => 'admin'
        
        ]);
        User::create([
        'name' => 'abdalmomn ',
        'phone' => '0911122233' ,
        'password' => Hash::make('12345678'),
        ]);
        User::create([
        'name' => 'abdalmomn ',
        'phone' => '0911122244' ,
        'password' => Hash::make('12345678'),
        ]);
        
    }
    
}
