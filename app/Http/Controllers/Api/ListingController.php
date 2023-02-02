<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Category,Product,ProductAttribute};

class ListingController extends Controller
{
    public function index(Request $request)
    {
        $category_id = Category::where('slug',$request->slug)->value('id');
        $orderBy = $request->orderBy ?? 'id';
        $orderByValue =  $request->orderByValue ?? 'asc';
        $query = Product::where('category_id',$category_id)->with('images')->orderBy($orderBy,$orderByValue);
        if($request->has('car_type'))
        {
            $query->whereHas('attributes',function($o){
                $o->where('key','car_type')->where('value',$request->car_type);
            });
        }
        if($request->has('make'))
        {
            $make = $request->make;
            $query->whereHas('attributes',function($i) use ($make){
                $i->where('key','make')->where('value',$make);
            });
        }
        if($request->has('model_from') && $request->has('model_to'))
        {
            $model_from = $request->model_from;
            $model_to = $request->model_to;
            $query->whereHas('attributes',function($l) use ($model_from,$model_to){
                $l->where('key','model')->whereBetween('value',[$model_from,$model_to]);
            });
        }
        if($request->has('price_from') && $request->has('price_to'))
        {
            $query->whereBetween('price',[$request->price_from,$request->price_to]);
        }
        if($request->has('mileage_from') && $request->has('mileage_to'))
        {
            $mileage_from = $request->mileage_from;
            $mileage_to = $request->mileage_to;
            $query->whereHas('attributes',function($m) use ($mileage_from,$mileage_to) {
                $m->where('key','mileage')->whereBetween('value',[$mileage_from,$mileage_to]);
            });
        }

        $products = $query->get();
        return $this->response(true,$products,'Products listing fetched successfully',200);
    }

    public function getProductById(Product $product)
    {   
        $product->load('images','attributes');
        return $this->response(true,$product,'Product fetched successfully',200);
    }

    public function makeList(){
        $arr = [];
        $att = ProductAttribute::where(['key'=>'make'])->get();
        foreach($att as $make)
        {
            $arr[] = $make->value;
        }
        return $this->response(true,$arr,'Car Make Fetched successfully',200);
    }

    public function modelList($make){
        $arr = [];
        $att = Product::whereHas('attributes',function($o) use ($make){
            $o->where('value',$make);
        })->get();
        foreach($att as $make)
        {
            $arr[] = $make->name;
        }
        return $this->response(true,$arr,'Car Model Fetched successfully',200);
    }
}
