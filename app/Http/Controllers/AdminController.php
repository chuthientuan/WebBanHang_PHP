<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Customer;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

session_start();
class AdminController extends Controller
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

    public function index()
    {
        return view('admin_login');
    }

    public function show_dashboard(Request $request)
    {
        $this->AuthLogin();
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $ordersQuery = Order::with('orderDetails.product')
            ->where('order_status', 3);

        if ($start_date && $end_date) {
            $startDateFormatted = Carbon::parse($start_date)->startOfDay();
            $endDateFormatted = Carbon::parse($end_date)->endOfDay();

            $ordersQuery->whereBetween('created_at', [$startDateFormatted, $endDateFormatted]);
        }

        $orders = $ordersQuery->get();
        $totalRevenue = $orders->sum(function ($order) {
            return $order->orderDetails->sum(function ($detail) {
                return ($detail->product ? $detail->product->product_price : 0) * $detail->product_sales_quantity;
            });
        });

        $totalOrders = $orders->count();

        $topProducts = collect();
        foreach ($orders as $order) {
            foreach ($order->orderDetails as $detail) {
                if ($detail->product) {
                    $topProducts->push([
                        'name' => $detail->product->product_name,
                        'quantity' => $detail->product_sales_quantity,
                    ]);
                }
            }
        }
        $topProducts = $topProducts
            ->groupBy('name')
            ->map(fn($items) => $items->sum('quantity'))
            ->sortDesc()
            ->take(5);

        $monthlyRevenue = $orders
            ->groupBy(fn($order) => Carbon::parse($order->created_at)->format('n'))
            ->sortKeys()
            ->map(function ($monthlyOrders) {
                return $monthlyOrders->sum(function ($order) {
                    return $order->orderDetails->sum(function ($detail) {
                        return ($detail->product ? $detail->product->product_price : 0) * $detail->product_sales_quantity;
                    });
                });
            });

        $chartMonthlyRevenue = [];
        for ($m = 1; $m <= 12; $m++) {
            $chartMonthlyRevenue[$m] = $monthlyRevenue->get($m, 0);
        }

        // Pass the selected dates back to the view
        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'topProducts',
            'chartMonthlyRevenue',
            'start_date',
            'end_date'
        ));
    }

    public function dashboard(Request $request)
    {
        $admin_email = $request->admin_email;
        $admin_password = md5($request->admin_password);

        $result = Admin::where('admin_email', $admin_email)
            ->where('admin_password', $admin_password)
            ->first();

        if ($result) {
            Session::put('admin_name', $result->admin_name);
            Session::put('admin_id', $result->admin_id);
            return Redirect::to('/dashboard');
        } else {
            // Đăng nhập thất bại
            return redirect()->back()->with('message', 'Email hoặc mật khẩu không đúng!');
        }
    }

    public function logOut()
    {
        $this->AuthLogin();
        Session::put('admin_name', null);
        Session::put('admin_id', null);
        return Redirect::to('/admin');
    }

    public function all_customer()
    {
        $this->AuthLogin();
        $customer = Customer::orderby('customer_id', 'ASC')->get();
        return view('admin.all-customer')->with(compact('customer'));
    }
}
