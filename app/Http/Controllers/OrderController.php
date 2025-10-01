<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Payment;
use App\Models\Shipping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class OrderController extends Controller
{
    public function AuthLogin()
    {
        $admin_id = Session::get('admin_id');
        if ($admin_id) {
            return Redirect::to('dashboard');
        } else {
            return Redirect::to('admin')->send();
        }
    }

    public function manage_order()
    {
        $this->AuthLogin();
        $order = Order::orderby('created_at', 'DESC')->get();
        return view('admin.manage_order')->with(compact('order'));
    }

    public function view_order($order_id)
    {
        $order_details = OrderDetails::where('order_id', $order_id)->get();
        $order = Order::where('order_id', $order_id)->get();
        foreach ($order as $key => $ord) {
            $customer_id = $ord->customer_id;
            $shipping_id = $ord->shipping_id;
            $payment_id = $ord->payment_id;
        }
        $customer = Customer::where('customer_id', $customer_id)->first();
        $shipping = Shipping::where('shipping_id', $shipping_id)->first();
        $payment = Payment::where('payment_id', $payment_id)->first();
        $product = OrderDetails::with('product')->where('order_id', $order_id)->get();

        foreach ($product as $key => $order_d) {
            $product_coupon = $order_d->product_coupon;
        }
        if ($product_coupon != 'no') {
            $coupon = Coupon::where('coupon_code', $product_coupon)->first();
            $coupon_condition = $coupon->coupon_condition;
            $coupon_number = $coupon->coupon_number;
        } else {
            $coupon_condition = 2;
            $coupon_number = 0;
        }

        return view('admin.view_order')->with(compact('order_details', 'customer', 'shipping', 'payment', 'product', 'coupon_condition', 'coupon_number'));
    }
}
