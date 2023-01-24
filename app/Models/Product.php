<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $guarded = ['id'];

    const STATUS_PENDING = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_FINISHED = 2;

    public function category(){
        return $this->belongsTo('App\Models\Category','category_id');
    }

    public function user(){
        return $this->belongsTo('App\Models\User','category_id');
    }

    public function images(){
        return $this->hasMany('App\Models\ProductImage','product_id');
    }

    public function attributes(){
        return $this->hasMany('App\Models\ProductAttribute','product_id');
    }

    public function bids(){
        return $this->hasMany('App\Models\ProductBid','product_id');
    }

}
