<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ProductBid extends Model
{
    use HasFactory;

    protected $table = 'bids';
    protected $guarded = ['id'];
    protected $appends = ['m_amount','m_created_at'];

    public function product(){
        return $this->belongsTo('App\Models\Product','product_id');
    }

    public function user(){
        return $this->belongsTo('App\Models\User','user_id');
    }

    public function getMAmountAttribute(){
        return 'Rs '.number_format($this->amount);
    }

    public function getMCreatedAtAttribute(){
        return Carbon::parse($this->created_at)->isoFormat('DD MMM, YYYY (h:mm a)');
    }

}
