<?php

// app/Http/Controllers/PaymentController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Razorpay\Api\Api;
use DB;

class PaymentController extends Controller
{
    public function createOrder(Request $request)
    {
        // $api = new Api('rzp_live_41K4CTkdTS8hJ0', '3vIpH0VdauCFIFQeKsMNVvSX'); //live

        $api = new Api('rzp_test_pQGlOyfBsVmbow', 'Akywxl3ew2TJ54WwuTItNCA8'); //test

        // return response()->json([]);

        $order = $api->order->create([
            'receipt'         => uniqid(),
            'amount'          => $request->amount * 100, // amount in paise
            // 'currency'        => 'INR',
            'currency'        => $request->currency,
            'payment_capture' => 1
        ]);

        return response()->json([
            'order_id' => $order->id,
            // 'key' => 'rzp_live_41K4CTkdTS8hJ0',
            'key' => 'rzp_test_pQGlOyfBsVmbow',
            'amount' => $request->amount * 100
        ]);
    }

    public function paymentSuccess(Request $request)
    {
        // validate and store payment info
        $razorpay_data = $request->response;
        $user_data = $request->paymentDetails;

        $save = DB::table('payments')->insert([
            'amount'=> $user_data['amount'],
            'order_details'=> json_encode($razorpay_data),
            'payment_details'=> json_encode($user_data)
        ]);



        $all = $request->all();
        return response()->json(['status' => 'success', 'razorpay_data'=>$razorpay_data, 'user_data'=> $user_data, 'all'=> $all]);
    }
}
