<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const STATUS_IN_PREPARATION = 'in_preparation';
    const STATUS_SENT = 'sent';
    const STATUS_RECEIVED = 'received';
    const PAYMENT_UNPAID = 'unpaid';
    const PAYMENT_PAID = 'paid';
    
    protected $table = 'orders';
    protected $fillable = ['id' ,'user_id', 'order_status' , 'payment_status'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function medicines()
    {
        return $this->belongsToMany(Medicine::class)->withPivot('quantity');
    }
    // public function orderStatus(){
    //     return $this->hasMany(orderStatus::class);
    // }
}
