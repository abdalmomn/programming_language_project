<?php

namespace Database\Seeders;

use App\Models\status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class statusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        status::create([
        'status' => 'processing'
        ]);
        status::create([
        'status' => 'sending'
        ]);
        status::create([
        'status' => 'sent'
        ]);
    }
}
