<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductBid extends Model
{
    use HasFactory;

    protected $table = 'bids';
    protected $guarded = ['id'];

    public function product(){
        return $this->belongsTo('App\Models\Product','product_id');
    }

    public function user(){
        return $this->belongsTo('App\Models\User','category_id');
    }

}
