<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function user(){
        return $this->belongsTo('App\Models\User','user_id');
    }

    public function wallet_logs(){
        return $this->hasMany('App\Models\WalletLogs','wallet_id');
    }

}
