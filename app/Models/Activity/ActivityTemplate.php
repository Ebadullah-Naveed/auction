<?php

namespace App\Models\Activity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityTemplate extends Model
{
    use HasFactory;

    protected $table = 'activity_template';
    public $timestamps = false;
    
}
