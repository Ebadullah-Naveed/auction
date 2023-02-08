<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Category,Product,ProductBid};
use Carbon\Carbon;

class BidController extends Controller
{
    public function index(Request $request)
    {
        $bid = ProductBid::where('user_id',auth()->user()->id)->with('product.images')->orderBy('amount','desc')->get()->unique('product_id');
        return $this->response(true,$bid,'Bid fetched successfully',200);
    }

    public function bidProduct(Request $request)
    {
        $product = Product::where('id',$request->product_id)->first();
        $productPrice = $product->last_bid ?? $product->price;
        $bidPrice = $request->amount+$productPrice;
        if(Carbon::now() > Carbon::parse($product->end_datetime))
        {
            return $this->response(false,null,'Bid is now closed',422);
        }
        if(!$product)
        {
            return $this->response(false,null,'Invalid Product Id',422);
        }
        if($product->min_increment == 0 && $product->max_increment == 0)
        {
            $bid = ProductBid::create([
                'user_id' => auth()->user()->id,
                'product_id' => $request->product_id,
                'amount' => $request->amount+$productPrice
            ]);
            $newDateTime = Carbon::now()->addMinutes(4);
            if($newDateTime > Carbon::parse($product->end_datetime))
            {
                $newEndTime = Carbon::parse($product->end_datetime)->addMinutes(5);
                Product::where('id',$request->product_id)->update(['end_datetime' => $newEndTime]);
            }
        }
        else if($request->amount >= $product->min_increment && $request->amount <= $product->max_increment)
        {
            $bid = ProductBid::create([
                'user_id' => auth()->user()->id,
                'product_id' => $request->product_id,
                'amount' => $request->amount+$productPrice
            ]);
            $newDateTime = Carbon::now()->addMinutes(4);
            if($newDateTime > Carbon::parse($product->end_datetime))
            {
                $newEndTime = Carbon::parse($product->end_datetime)->addMinutes(5);
                Product::where('id',$request->product_id)->update(['end_datetime' => $newEndTime]);
            }
        }
        else
        {
            return $this->response(false,null,'Invalid Amount',422);
        }
        // Product::where('id',$request->product_id)->update(['last_bid'=>$request->amount+$productPrice]);
        return $this->response(true,$bid,'Bid placed successfully',200);
    }
}
