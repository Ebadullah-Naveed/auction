<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductWinner extends Model
{
    use HasFactory;

    protected $table = 'product_winner';

    const STATUS_PENDING = 0;
    const STATUS_PROCESSING = 1;
    const STATUS_COMPLETED = 2;
    const STATUS_CANCELLED = 3;

    public function user(){
        return $this->belongsTo('App\Models\User','user_id');
    }

    public function product(){
        return $this->belongsTo('App\Models\Product','product_id');
    }

    public function bid(){
        return $this->belongsTo('App\Models\Bid','bid_id');
    }

}
