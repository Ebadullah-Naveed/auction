<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use DB;
use App\Models\WalletLogs;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function response($success,$data = null,$message,$statusCode)
    {
        return response()->json([
            'success' => $success,
            'data' => $data,
            'message' => $message,
        ], $statusCode);
    }

    public function imageUpload($image,$path)
    {
        try
        {
            $paths = $image->store('cnic', 'public');
            $storage_path = $paths;
            return '/'.'storage/'.$storage_path;
        }
        catch(\Exception $e)
        {
            return false;
        }
    }

    function get_client_ip() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    public function wallet($amount,$type,$response=null,$productId){
        $wallet = auth()->user()->wallet()->first();
        $balance = $type == 'credited' ? $wallet->balance + $amount : $wallet->balance - $amount;
        $wallet->update(['balance'=> $balance]);
        WalletLogs::create(['user_id'=>auth()->user()->id,'wallet_id'=>$wallet->id,'product_id'=>$productId,'type'=>$type,'amount'=>$amount,'server_response'=>$response]);
        return true;
    }
}
