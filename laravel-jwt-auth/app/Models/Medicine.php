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
        'user_id'
    ];

    public function user(){
        $this->belongsTo(User::class);
    }

    


}
