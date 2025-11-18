<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
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

    public function manage_order(Request $request)
    {
        $this->AuthLogin();
        $status = $request->input('status');
        $ordersQuery = Order::with('customer')->orderBy('order_id', 'desc');
        if ($status !== null && $status !== 'all') {
            $ordersQuery->where('order_status', $status);
        }
        $all_order = $ordersQuery->paginate(10);
        return view('admin.manage_order')
            ->with('all_order', $all_order)
            ->with('status', $status);
    }

    public function view_order($order_id)
    {
        // 1. Lấy tất cả thông tin chỉ bằng MỘT truy vấn duy nhất!
        $order = Order::with('customer', 'shipping', 'payment', 'orderDetails.product')
            ->find($order_id);

        // 2. Xử lý coupon (logic an toàn hơn)
        // Giả định coupon áp dụng cho cả đơn hàng, lấy từ chi tiết đầu tiên
        $first_detail = $order->orderDetails->first();
        $product_coupon = $first_detail ? $first_detail->product_coupon : 'no';

        // Đặt giá trị mặc định
        $coupon_condition = 2;
        $coupon_number = 0;

        if ($product_coupon != 'no') {
            $coupon = Coupon::where('coupon_code', $product_coupon)->first();
            // Kiểm tra xem coupon có hợp lệ không
            if ($coupon) {
                $coupon_condition = $coupon->coupon_condition;
                $coupon_number = $coupon->coupon_number;
            }
        }

        // 3. Trả về view với chỉ 3 biến
        return view('admin.view_order')->with(compact('order', 'coupon_condition', 'coupon_number'));
    }

    public function delete_order($order_id)
    {
        $this->AuthLogin();
        Order::where('order_id', $order_id)->delete();
        Session::put('message', 'Xóa đơn hàng thành công');
        return Redirect::to('/manage-order');
    }

    // Trong file OrderController.php
    public function update_order_quantity_status(Request $request)
    {
        // Lấy dữ liệu từ request
        $order_id = $request->order_id;
        $new_status = $request->order_status;
        $product_ids = $request->order_product_id;
        $quantities = $request->quantity;

        // Lấy thông tin đơn hàng hiện tại
        $order = Order::find($order_id);
        $previous_status = $order->order_status;

        // --- CÁC TRƯỜNG HỢP LIÊN QUAN ĐẾN KHO ---

        // Chuyển sang trạng thái GIAO HÀNG hoặc ĐÃ GIAO (trừ kho)
        if (($new_status == 2 || $new_status == 3) && ($previous_status == 1 || $previous_status == 4)) {
            $is_stock_sufficient = true;
            $invalid_products = [];

            // 1. Kiểm tra số lượng tồn kho trước
            foreach ($product_ids as $key => $product_id) {
                $product = Product::find($product_id);
                $quantity_sold = $quantities[$key];

                if ($product->product_quantity < $quantity_sold) {
                    $is_stock_sufficient = false;
                    $invalid_products[] = $product_id;
                }
            }

            // 2. Nếu không đủ hàng, trả về lỗi
            if (!$is_stock_sufficient) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không đủ số lượng hàng trong kho!',
                    'invalid_products' => $invalid_products
                ]);
            }

            // 3. Nếu đủ hàng, tiến hành trừ kho
            foreach ($product_ids as $key => $product_id) {
                $product = Product::find($product_id);
                $quantity_sold = $quantities[$key];

                $product->product_quantity -= $quantity_sold;
                $product->save();
            }
        }
        // Chuyển sang trạng thái HỦY ĐƠN (hoàn kho)
        elseif ($new_status == 4 && ($previous_status == 2 || $previous_status == 3)) {
            // Chỉ hoàn lại kho nếu đơn hàng trước đó đã ở trạng thái đã xử lý/giao hàng
            foreach ($product_ids as $key => $product_id) {
                $product = Product::find($product_id);
                $quantity_sold = $quantities[$key];

                $product->product_quantity += $quantity_sold;
                $product->save();
            }
        }

        // --- CẬP NHẬT TRẠNG THÁI CUỐI CÙNG ---
        $order->order_status = $new_status;
        $order->save();

        return response()->json(['status' => 'success', 'message' => 'Cập nhật trạng thái đơn hàng thành công.']);
    }

    public function history(Request $request)
    {
        if (!Session::has('customer_id')) {
            return redirect('/login-checkout')->with('error', 'Vui lòng đăng nhập để xem lịch sử mua hàng.');
        }
        $cate_product = Category::where('category_status', '1')->orderBy('category_id', 'desc')->get();
        $brand_product = Brand::where('brand_status', '1')->orderBy('brand_id', 'desc')->get();

        $meta_title = "Lịch sử mua hàng";
        $url_canonical = $request->url();

        $orders = Order::where('customer_id', Session::get('customer_id'))
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('pages.history.history')
            ->with('category', $cate_product)
            ->with('brand', $brand_product)
            ->with('meta_title', $meta_title)
            ->with('url_canonical', $url_canonical)
            ->with('orders', $orders);
    }

    public function view_history_order(Request $request, $order_id)
    {
        if (!Session::has('customer_id')) {
            return redirect('/login-checkout')->with('error', 'Vui lòng đăng nhập để xem lịch sử mua hàng.');
        }

        // Lấy các dữ liệu chung
        $cate_product = Category::where('category_status', '1')->orderBy('category_id', 'desc')->get();
        $brand_product = Brand::where('brand_status', '1')->orderBy('brand_id', 'desc')->get();
        $meta_title = "Chi tiết đơn hàng";
        $url_canonical = $request->url();

        // Lấy chi tiết đơn hàng
        $order = Order::with('customer', 'shipping', 'payment', 'orderDetails.product')
            ->where('customer_id', Session::get('customer_id')) // Thêm điều kiện bảo mật
            ->where('order_id', $order_id)
            ->first();

        // Kiểm tra nếu không tìm thấy đơn hàng hoặc đơn hàng không thuộc về khách hàng
        if (!$order) {
            return redirect('/history')->with('error', 'Đơn hàng không tồn tại.');
        }

        // (Code xử lý coupon có thể giữ nguyên nếu bạn cần)
        $first_detail = $order->orderDetails->first();
        $product_coupon = $first_detail ? $first_detail->product_coupon : 'no';

        $coupon_condition = 2;
        $coupon_number = 0;

        if ($product_coupon != 'no') {
            $coupon = Coupon::where('coupon_code', $product_coupon)->first();
            if ($coupon) {
                $coupon_condition = $coupon->coupon_condition;
                $coupon_number = $coupon->coupon_number;
            }
        }
        // Trả về view và TRUYỀN BIẾN $order
        return view('pages.history.view_history_order')
            ->with('category', $cate_product)
            ->with('brand', $brand_product)
            ->with('meta_title', $meta_title)
            ->with('url_canonical', 'url_canonical')
            ->with('order', $order)
            ->with('coupon_condition', $coupon_condition)
            ->with('coupon_number', $coupon_number);
    }

    public function cancel_order($order_id)
    {
        if (!Session::has('customer_id')) {
            return redirect('/login-checkout')->with('error', 'Vui lòng đăng nhập để thực hiện thao tác này.');
        }

        $order = Order::where('order_id', $order_id)
            ->where('customer_id', Session::get('customer_id'))
            ->first();

        if ($order && $order->order_status == 1) {
            $order->order_status = 4;
            $order->save();

            return Redirect::to('/history')->with('message', 'Hủy đơn hàng thành công.');
        }

        return Redirect::to('/history')->with('error', 'Đơn hàng không thể hủy hoặc không tồn tại.');
    }
}
