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
        $bid = ProductBid::where('user_id',auth()->user()->id)->with('product.images')->orderBy('amount','desc')->get()->unique('product_id')->values();
        return $this->response(true,$bid,'Bid fetched successfully',200);
    }

    public function bidProduct(Request $request)
    {
        $product = Product::where('id',$request->product_id)->first();
        if(!$product)
        {
            return $this->response(false,null,'Invalid Product Id',422);
        }
        $latestBid = ProductBid::where('product_id',$request->product_id)->orderBy('created_at','DESC')->first();
        if($latestBid != null && auth()->user()->id == $latestBid->user_id)
        {
            return $this->response(false,null,'You have placed the last bid on this product',422);
        }
        $productPrice = $latestBid->amount ?? $product->price;
        $bidPrice = $request->amount+$productPrice;
        if(Carbon::now() > Carbon::parse($product->end_datetime))
        {
            return $this->response(false,null,'Bid is now closed',422);
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
        return $this->response(true,$bid,'Bid placed successfully',200);
    }
}
