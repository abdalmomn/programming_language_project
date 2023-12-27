<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'theScientificName' ,
        'tradeName',
        'category',
        'theManufactureCompany',
        'quantity',
        'validity',
        'price',
    ];
    public function category(){
        $this->belongsTo(category::class);
    }
}
