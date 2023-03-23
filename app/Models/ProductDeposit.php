<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDeposit extends Model
{
    use HasFactory;

    protected $table = 'product_deposit';

    public function user(){
        return $this->belongsTo('App\Models\User','user_id');
    }

    public function product(){
        return $this->belongsTo('App\Models\Product','product_id');
    }

}
