<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Category,Product,ProductBid};
use Carbon\Carbon;
use DB;
use Helper;

class BidController extends Controller
{
    public function index(Request $request)
    {
        $bid = ProductBid::where('user_id',auth()->user()->id)->with('product.images')->orderBy('amount','desc')->get()->unique('product_id')->values();
        return $this->response(true,$bid,'Bid fetched successfully',200);
    }

    public function bidProduct(Request $request)
    {
        try
        {
            DB::beginTransaction();
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
            $lastBid = ProductBid::where('user_id',auth()->user()->id)->where('product_id',$product->id)->first();
            if(!$lastBid)
            {
                $wallet = auth()->user()->wallet()->first();
                $balance = $product->price * 0.25;
                if($wallet->balance < $balance)
                {
                    $amount = $balance - $wallet->balance;
                    $payment = $this->makePayment($product->id,$amount);
                    if($payment == false)
                    {
                        return $this->response(false,null,'Payment fail',422);
                    }
                    if($amount > 0)
                    {
                        $amount2 = $balance - $amount; 
                        $this->wallet($amount2,'debited',json_decode($request));
                    }
                }
                else
                {
                    $this->wallet($balance,'debited',json_decode($request));
                }
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
                    'amount' => $bidPrice
                ]);
                $now = Carbon::now();
                $endTime = Carbon::parse($product->end_datetime);
                if($endTime->diffInMinutes($now, true) <= 4)
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
                    'amount' => $bidPrice
                ]);
                $now = Carbon::now();
                $endTime = Carbon::parse($product->end_datetime);
                if($endTime->diffInMinutes($now, true) <= 4)
                {
                    $newEndTime = Carbon::parse($product->end_datetime)->addMinutes(5);
                    Product::where('id',$request->product_id)->update(['end_datetime' => $newEndTime]);
                }
            }
            else
            {
                return $this->response(false,null,'Invalid Amount',422);
            }
            DB::commit();
            $data = [
                'product_id' => $request->product_id,
                'last_bid' => $bidPrice
            ];
            $helper = new Helper;
            $helper->generalEvent($data);
            return $this->response(true,$bid,'Bid placed successfully',200);
        }
        catch(\Exception $e){
            return $this->response(false,null,$e->getMessage(),422);
        }
    }

    public function makePayment($productId,$amount){
        $payment = auth()->user()->payments()->create(['product_id'=>$productId,'transaction_id'=>'12345','amount'=>$amount,'status'=>'completed']);
        return true;
    }
}
