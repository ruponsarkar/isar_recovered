<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Razorpay\Api\Api;

class PaymentController extends Controller
{
    public function index() { return view('pages.payment'); }
    public function details(Request $request) { $data=$request->validate(['name'=>'required|string|max:150','email'=>'required|email','amount'=>'required|numeric|min:1','reference'=>'nullable|string|max:100']); return view('pages.payment-details', compact('data')); }
    public function order(Request $request) {
        $data=$request->validate(['amount'=>'required|numeric|min:1','reference'=>'nullable|string|max:100']);
        abort_unless(env('RAZORPAY_KEY_ID') && env('RAZORPAY_KEY_SECRET'), 503, 'Online payments are not configured.');
        $order=(new Api(env('RAZORPAY_KEY_ID'), env('RAZORPAY_KEY_SECRET')))->order->create(['receipt'=>$data['reference'] ?? 'isar-'.time(),'amount'=>(int) round($data['amount']*100),'currency'=>'INR']);
        return response()->json(['id'=>$order['id'],'key'=>env('RAZORPAY_KEY_ID'),'amount'=>$order['amount'],'currency'=>'INR']);
    }
    public function success(Request $request) { $request->validate(['razorpay_payment_id'=>'required|string','razorpay_order_id'=>'required|string','razorpay_signature'=>'required|string']); return redirect()->route('payment')->with('success','Payment confirmation received. Thank you.'); }
}
