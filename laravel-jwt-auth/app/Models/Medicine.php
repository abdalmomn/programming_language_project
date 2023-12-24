<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [
        'theScientificName' ,
        'tradeName',
        'category',
        'theManufactureCompany',
        'quantityAvailable',
        'validity',
        'price',
    ];
    use HasFactory;
}
