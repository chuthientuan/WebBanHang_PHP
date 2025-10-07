<?php

namespace App\Http\Controllers;

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

    public function manage_order()
    {
        $this->AuthLogin();
        $order = Order::orderby('created_at', 'DESC')->get();
        return view('admin.manage_order')->with(compact('order'));
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

        // --- TRƯỜNG HỢP 1: CẬP NHẬT TRẠNG THÁI THÀNH "ĐÃ XỬ LÝ - ĐÃ GIAO HÀNG" (status = 2) ---
        if ($new_status == 2) {
            $is_stock_sufficient = true;

            // 1. Kiểm tra số lượng tồn kho trước
            foreach ($product_ids as $key => $product_id) {
                $product = Product::find($product_id);
                $quantity_sold = $quantities[$key];

                if ($product->product_quantity < $quantity_sold) {
                    $is_stock_sufficient = false;
                    break; // Dừng lại ngay khi có 1 sản phẩm không đủ
                }
            }

            // 2. Nếu tất cả sản phẩm đều đủ hàng
            if ($is_stock_sufficient) {
                // Chỉ trừ kho nếu đơn hàng trước đó chưa được xử lý
                if ($previous_status != 2) {
                    foreach ($product_ids as $key => $product_id) {
                        $product = Product::find($product_id);
                        $quantity_sold = $quantities[$key];

                        $product->product_quantity -= $quantity_sold;
                        $product->product_sold += $quantity_sold;
                        $product->save();
                    }
                }

                // Cập nhật trạng thái đơn hàng
                $order->order_status = $new_status;
                $order->save();
                return response()->json(['status' => 'success', 'message' => 'Cập nhật đơn hàng thành công và đã trừ kho.']);
            } else {
                // 3. Nếu không đủ hàng, trả về lỗi
                return response()->json(['status' => 'error', 'message' => 'Không đủ số lượng hàng trong kho!']);
            }
        }

        // --- TRƯỜNG HỢP 2: CẬP NHẬT TRẠNG THÁI THÀNH "HỦY ĐƠN HÀNG" (status = 3) ---
        elseif ($new_status == 3) {
            // Chỉ hoàn lại kho nếu đơn hàng trước đó đã ở trạng thái "Đã xử lý"
            if ($previous_status == 2) {
                foreach ($product_ids as $key => $product_id) {
                    $product = Product::find($product_id);
                    $quantity_sold = $quantities[$key];

                    $product->product_quantity += $quantity_sold;
                    $product->product_sold -= $quantity_sold;
                    $product->save();
                }
            }

            // Cập nhật trạng thái đơn hàng
            $order->order_status = $new_status;
            $order->save();
            return response()->json(['status' => 'success', 'message' => 'Đã hủy đơn hàng và hoàn lại kho (nếu cần).']);
        }

        // --- TRƯỜNG HỢP 3: CÁC TRẠNG THÁI KHÁC (ví dụ: quay về "Chưa xử lý") ---
        else {
            // Chỉ cập nhật trạng thái mà không ảnh hưởng đến kho
            $order->order_status = $new_status;
            $order->save();
            return response()->json(['status' => 'success', 'message' => 'Cập nhật trạng thái đơn hàng thành công.']);
        }
    }
}
