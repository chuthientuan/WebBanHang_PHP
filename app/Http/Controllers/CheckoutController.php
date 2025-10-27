<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\City;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Models\Customer;
use App\Models\Feeship;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Payment;
use App\Models\Shipping;
use App\Models\Province;
use App\Models\Ward;

class CheckoutController extends Controller
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

    public function login_checkout()
    {
        $cate_product = Category::where('category_status', '1')->orderBy('category_id', 'desc')->get();
        $brand_product = Brand::where('brand_status', '1')->orderBy('brand_id', 'desc')->get();
        return view('pages.checkout.login_checkout')->with('category', $cate_product)->with('brand', $brand_product);
    }

    public function login_home()
    {
        $cate_product = Category::where('category_status', '1')->orderBy('category_id', 'desc')->get();
        $brand_product = Brand::where('brand_status', '1')->orderBy('brand_id', 'desc')->get();
        return view('pages.login.login')->with('category', $cate_product)->with('brand', $brand_product);
    }

    public function add_customer(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|unique:tbl_customer,customer_email',
            'customer_phone' => 'required|string|max:15',
            'customer_password' => 'required|string|min:8|confirmed' // Thêm 'confirmed'
        ], [
            // Tùy chỉnh thông báo lỗi (tùy chọn)
            'customer_name.required' => 'Vui lòng nhập họ và tên.',
            'customer_email.required' => 'Vui lòng nhập địa chỉ email.',
            'customer_email.email' => 'Địa chỉ email không hợp lệ.',
            'customer_email.unique' => 'Email này đã được sử dụng, vui lòng chọn một email khác.',
            'customer_password.required' => 'Vui lòng nhập mật khẩu.',
            'customer_password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'customer_password.confirmed' => 'Mật khẩu nhập lại không khớp.'
        ]);
        $data = array();
        $data['customer_name'] = $request->customer_name;
        $data['customer_email'] = $request->customer_email;
        $data['customer_password'] = md5($request->customer_password);
        $data['customer_phone'] = $request->customer_phone;

        $customer = Customer::create($data);
        Session::put('customer_id', $customer->customer_id);
        Session::put('customer_name', $customer->customer_name);
        return Redirect::to('/checkout');
    }

    public function checkout()
    {
        $cate_product = Category::where('category_status', '1')->orderBy('category_id', 'desc')->get();
        $brand_product = Brand::where('brand_status', '1')->orderBy('brand_id', 'desc')->get();
        $city = City::orderby('matp', 'ASC')->get();
        return view('pages.checkout.show_checkout')->with('category', $cate_product)->with('brand', $brand_product)
            ->with('city', $city);
    }

    public function save_checkout_customer(Request $request)
    {
        $request->validate([
            'shipping_name' => 'required',
            'shipping_address' => 'required',
            'shipping_phone' => 'required',
            'shipping_email' => 'required|email',
        ]);

        $data = array();
        $data['shipping_name'] = $request->shipping_name;
        $data['shipping_address'] = $request->shipping_address;
        $data['shipping_phone'] = $request->shipping_phone;
        $data['shipping_email'] = $request->shipping_email;
        $data['shipping_note'] = ($request->shipping_note);
        $data['customer_id'] = Session::get('customer_id');

        $shipping = Shipping::create($data);
        Session::put('shipping_id', $shipping->shipping_id);

        return Redirect::to('/payment');
    }

    public function payment()
    {
        $cate_product = Category::where('category_status', '1')->orderBy('category_id', 'desc')->get();
        $brand_product = Brand::where('brand_status', '1')->orderBy('brand_id', 'desc')->get();
        return view('pages.checkout.payment')->with('category', $cate_product)->with('brand', $brand_product);
    }

    public function order_place(Request $request)
    {
        //Insert payment method
        $payment_data = [
            'payment_method' => $request->payment_option
        ];
        $payment = Payment::create($payment_data);

        //Insert order
        $order_data = [
            'customer_id' => Session::get('customer_id'),
            'shipping_id' => Session::get('shipping_id'),
            'payment_id' => $payment->payment_id,
            'order_total' => Cart::total(),
            'order_status' => 'Đang chờ xử lý'
        ];
        $order = Order::create($order_data);
        //Insert order details
        $content = Cart::content();
        foreach ($content as $v_content) {
            $order_details_data = [
                'order_id' => $order->order_id,
                'product_id' => $v_content->id,
                'product_name' => $v_content->name,
                'product_price' => $v_content->price,
                'product_sales_quantity' => $v_content->qty,
            ];
            OrderDetails::create($order_details_data);
        }

        if ($payment_data['payment_method'] == 1) {
            echo 'Thanh toán thẻ ATM';
        } elseif ($payment_data['payment_method'] == 2) {
            Cart::destroy();
            $cate_product = Category::where('category_status', '1')->orderBy('category_id', 'desc')->get();
            $brand_product = Brand::where('brand_status', '1')->orderBy('brand_id', 'desc')->get();
            return view('pages.checkout.handcash')->with('category', $cate_product)->with('brand', $brand_product);
        } else {
            echo 'Thẻ ghi nợ';
        }
    }

    public function logout_checkout()
    {
        Session::flush();
        return Redirect::to('/login-checkout');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email_account' => 'required|email',
            'password_account' => 'required'
        ]);
        $email = $request->email_account;
        $password = md5($request->password_account);

        $result = Customer::where('customer_email', $email)->where('customer_password', $password)->first();
        if ($result) {
            Session::put('customer_id', $result->customer_id);
            return Redirect::to('/trang-chu');
        } else {
            return Redirect::to('/login-checkout')->with('error', 'Tài khoản hoặc mật khẩu không chính xác.');
        }
    }

    public function login_customer(Request $request)
    {
        $request->validate([
            'email_account' => 'required|email',
            'password_account' => 'required'
        ]);
        $email = $request->email_account;
        $password = md5($request->password_account);

        $result = Customer::where('customer_email', $email)->where('customer_password', $password)->first();
        if ($result) {
            Session::put('customer_id', $result->customer_id);
            return Redirect::to('/checkout');
        } else {
            return Redirect::to('/login-checkout')->with('error', 'Tài khoản hoặc mật khẩu không chính xác.');
        }
    }

    public function manage_order()
    {
        $this->AuthLogin();
        $all_order = Order::with('customer')->orderBy('order_id', 'desc')->get();
        return view('admin.manage_order')->with('all_order', $all_order);
    }

    public function select_delivery_home(Request $request)
    {
        $data = $request->all();
        if ($data['action']) {
            $output = '';
            if ($data['action'] == "city") {
                $select_province = Province::where('matp', $data['ma_id'])->orderby('maqh', 'ASC')->get();
                $output .= '<option>----Chọn quận huyện----</option>';
                foreach ($select_province as $key => $province) {
                    $output .= '<option value="' . $province->maqh . '">' . $province->name_province . '</option>';
                }
            } else {
                $select_wards = Ward::where('maqh', $data['ma_id'])->orderby('xaid', 'ASC')->get();
                $output .= '<option>----Chọn xã phường----</option>';
                foreach ($select_wards as $key => $ward) {
                    $output .= '<option value="' . $ward->xaid . '">' . $ward->name_ward . '</option>';
                }
            }
            return response($output);
        }
    }

    public function calculate_fee(Request $request)
    {
        $data = $request->all();
        if ($data['matp']) {
            $feeship = Feeship::where('fee_matp', $data['matp'])->where('fee_maqh', $data['maqh'])->where('fee_xaid', $data['xaid'])->get();
            if ($feeship) {
                $count_feeship = $feeship->count();
                if ($count_feeship > 0) {
                    foreach ($feeship as $key => $fee) {
                        Session::put('fee', $fee->fee_feeship);
                        Session::save();
                    }
                } else {
                    Session::put('fee', 10000);
                    Session::save();
                }
            }
        }
    }

    public function del_fee()
    {
        Session::forget('fee');
        return redirect()->back();
    }

    public function confirm_order(Request $request)
    {
        $data = $request->all();

        $shipping = new Shipping();
        $shipping->shipping_name = $data['shipping_name'];
        $shipping->shipping_email = $data['shipping_email'];
        $shipping->shipping_phone = $data['shipping_phone'];
        $shipping->shipping_address = $data['shipping_address'];
        $shipping->shipping_note = $data['shipping_note'];
        $shipping->save();

        $shipping_id = $shipping->shipping_id;

        $payment = new Payment();
        $payment->payment_method = $data['payment_method'];
        $payment->save();
        $payment_id = $payment->payment_id;

        $order = new Order();
        $order->customer_id = Session::get('customer_id');
        $order->shipping_id = $shipping_id;
        $order->payment_id = $payment_id;
        $order->order_status = 1;
        if (isset($data['order_code'])) {
            $order->order_code = $data['order_code']; // Lấy code từ QR
        } else {
            $order->order_code = substr(md5(microtime()), rand(0, 26), 5); // Tạo code mới (cho tiền mặt)
        }
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $order->created_at = now();
        $order->save();
        $order_id = $order->order_id;

        if (Session::get('cart') == true) {
            foreach (Session::get('cart') as $key => $cart) {
                $order_details = new OrderDetails();
                $order_details->order_id = $order_id;
                $order_details->product_id = $cart['product_id'];
                $order_details->product_sales_quantity = $cart['product_qty'];
                $order_details->product_coupon = $data['order_coupon'];
                $order_details->product_feeship = $data['order_fee'];
                $order_details->save();
            }
        }
        Cart::destroy();
        Session::forget(['coupon', 'fee', 'cart']);

        return response()->json([
            'status' => 'success_saved',
            'message' => 'Đã lưu đơn hàng thành công.'
        ]);
    }

    public function generate_qr_code(Request $request)
    {
        try {
            $finalAmount = Session::get('total');

            // 2. Tạo mã đơn hàng (để gửi cho QR và lưu sau)
            $order_code = substr(md5(microtime()), rand(0, 26), 5);

            // 3. Tạo URL mã QR
            $qrCodeUrl = 'https://api.vietqr.io/image/970436-0987654321-2P2t0j9.jpg?accountName=Test&amount=' . $finalAmount . '&addInfo=DH' . $order_code;

            // 4. Trả về thông tin cho AJAX
            return response()->json([
                'status' => 'qr_generated',
                'qr_data' => $qrCodeUrl,
                'order_code' => $order_code,
                'amount' => $finalAmount
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Lỗi khi tạo mã QR: ' . $e->getMessage()], 500);
        }
    }
}
