<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Category,Product};

class HomeController extends Controller
{
    public function index()
    {
        $cat = Category::get();
        foreach($cat as $key=>$cats)
        {
            $prod = Product::where('category_id',$cats->id)->count();
            $cat[$key]['product_count'] = $prod;
        }

        return $this->response(true,$cat,'Homepage fetched successfully',200);
    }
}
