<?php

namespace Database\Seeders;

use App\Models\orderStatus;
use App\Models\status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class statusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('order_status')->insert([
        'status' => 'processing'
        ]);
        DB::table('order_status')->insert([
        'status' => 'sending'
        ]);
        DB::table('order_status')->insert([
        'status' => 'sent'
        ]);
    }
}
