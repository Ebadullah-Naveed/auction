<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use HasFactory;

    static protected function getByEid($id){
        $id = decrypt($id);
        return self::findOrFail($id);
    } 

}
