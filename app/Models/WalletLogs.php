<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletLogs extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    const TYPE_CREDIT = 'credited';
    const TYPE_DEBIT = 'debited';

}
