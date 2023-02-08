<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Category,Product,ProductBid};

class BidController extends Controller
{
    public function index(Request $request)
    {
        $bid = ProductBid::where('user_id',auth()->user()->id)->with('product.images')->get();
        return $this->response(true,$bid,'Bid fetched successfully',200);
    }

    public function bidProduct(Request $request)
    {
        $product = Product::where('id',$request->product_id)->first();
        if(!$product)
        {
            return $this->response(false,null,'Invalid Product Id',422);
        }
        if($product->min_increment == 0 && $product->max_increment == 0)
        {
            $bid = ProductBid::create([
                'user_id' => auth()->user()->id,
                'product_id' => $request->product_id,
                'amount' => $request->amount
            ]);
        }
        else if($request->amount >= $product->min_increment && $request->amount <= $product->max_increment)
        {
            $bid = ProductBid::create([
                'user_id' => auth()->user()->id,
                'product_id' => $request->product_id,
                'amount' => $request->amount
            ]);
        }
        else
        {
            return $this->response(false,null,'Invalid Amount',422);
        }

        return $this->response(true,$bid,'Bid placed successfully',200);
    }
}
