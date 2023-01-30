<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $table = 'product_image';
    protected $guarded = ['id'];
    protected $appends = ['image_url'];
    // public $timestamps = false;

    const TYPE_IMAGE = 'image';
    const TYPE_VIDEO = 'video';

    public function getImageUrlAttribute(){
        return asset('storage/'.$this->source);
    }


}
