<?php

namespace Database\Seeders;

use App\Models\category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
        ['categories' => 'ادوية ضغط الدم'],
        ['categories' => 'ادوية  السكري'],
        ['categories' => 'ادوية مضادات الاكتئاب'],
        // ['categories' => 'ادوية العصبي'],
        // ['categories' => 'ادوية المعدة'],
        // ['categories' => 'ادوية مضادات حيوية'],
        // ['categories' => 'ادوية مسكنات'],
        ];
        
        foreach ($categories as $category) {
            category::create($category);
        }
    }
}
