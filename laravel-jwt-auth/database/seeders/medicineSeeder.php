<?php

namespace Database\Seeders;

use App\Models\Medicine;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class medicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    
        $medicines = [
        [
        'theScientificName' => 'ثيازيد' ,
        'tradeName' => 'هيدريتك  ',
        'category' => 'ادوية ضغط الدم',
        'theManufactureCompany' => 'فارما',
        'quantity' => '100',
        'validity' => '2025',
        'price' => '2500',
        ],
        [
        'theScientificName' => 'انداباميد' ,
        'tradeName' => 'ناتريليكس ',
        'category' => 'ادوية ضغط الدم',
        'theManufactureCompany' => 'فارما',
        'quantity' => '100',
        'validity' => '2025',
        'price' => '2500',
        ],
        [
        'theScientificName' => 'ثايزوليدينيديونز' ,
        'tradeName' => 'بايوغليفوزون ',
        'category' => 'ادوية السكري',
        'theManufactureCompany' => 'فارما',
        'quantity' => '100',
        'validity' => '2025',
        'price' => '2500',
        ],
        [
        'theScientificName' => 'ثايزوليدينيديونز' ,
        'tradeName' => 'وروزيغاليتوزون ',
        'category' => 'ادوية السكري',
        'theManufactureCompany' => 'فارما',
        'quantity' => '100',
        'validity' => '2025',
        'price' => '2500',
        ],
        [
        'theScientificName' => 'ترانيلسيبرومين' ,
        'tradeName' => 'بارنات',
        'category' => 'مضادات الاكتئاب',
        'theManufactureCompany' => 'فارما',
        'quantity' => '100',
        'validity' => '2025',
        'price' => '2500',
        ],
        [
        'theScientificName' => 'ترازودون' ,
        'tradeName' => 'موليباكسين',
        'category' => 'مضادات الاكتئاب',
        'theManufactureCompany' => 'فارما',
        'quantity' => '100',
        'validity' => '2025',
        'price' => '2500',
        ],
        [
        'theScientificName' => 'فينلافاكسين' ,
        'tradeName' => 'إيفكسور',
        'category' => 'مضادات الاكتئاب',
        'theManufactureCompany' => 'فارما',
        'quantity' => '100',
        'validity' => '2025',
        'price' => '2500',
        ]
        ];
        foreach ($medicines as $medicine) {
            Medicine::create($medicine);
        }
    }
}
