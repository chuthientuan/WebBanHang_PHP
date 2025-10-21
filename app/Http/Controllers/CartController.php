<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class CartController extends Controller
{
    public function save_cart(Request $request)
    {
        $product_id = $request->productid_hidden;
        $quantity = (int)$request->qty;

        $product_info = Product::where('product_id', $product_id)->first();

        if ($product_info) {
            // ================== BỔ SUNG LOGIC KIỂM TRA TỒN KHO ==================
            if ($quantity > $product_info->product_quantity) {
                // Nếu số lượng yêu cầu lớn hơn số lượng trong kho, trả về lỗi
                return response()->json([
                    'status' => 'error',
                    'message' => 'Số lượng bạn chọn vượt quá số lượng tồn kho (Chỉ còn ' . $product_info->product_quantity . ' sản phẩm).'
                ], 400); // 400 Bad Request là một mã lỗi HTTP phù hợp
            }
            // ======================================================================

            // Nếu số lượng hợp lệ, tiếp tục xử lý thêm vào giỏ hàng
            $cart = Session::get('cart', []);
            $session_id = substr(md5(microtime()), rand(0, 26), 5);

            // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa để cộng dồn
            $existing_key = null;
            foreach ($cart as $key => $item) {
                if ($item['product_id'] == $product_id) {
                    $existing_key = $key;
                    break;
                }
            }

            if ($existing_key !== null) {
                // Cộng dồn số lượng
                $cart[$existing_key]['product_qty'] += $quantity;
            } else {
                // Thêm mới sản phẩm
                $cart[$session_id] = [
                    'session_id' => $session_id,
                    'product_id' => $product_info->product_id,
                    'product_name' => $product_info->product_name,
                    'product_image' => $product_info->product_image,
                    'product_price' => $product_info->product_price,
                    'product_qty' => $quantity,
                ];
            }

            Session::put('cart', $cart);
            Session::save();

            return response()->json([
                'status' => 'success',
                'message' => 'Sản phẩm đã được thêm vào giỏ hàng!'
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'Sản phẩm không tồn tại!'], 404);
    }

    public function show_cart()
    {
        $cate_product = Category::where('category_status', '1')->orderBy('category_id', 'desc')->get();
        $brand_product = Brand::where('brand_status', '1')->orderBy('brand_id', 'desc')->get();
        return view('pages.cart.show_cart')->with('category', $cate_product)->with('brand', $brand_product);
    }

    public function delete_to_cart($rowId)
    {
        Cart::update($rowId, 0);
        return Redirect::to('/show-cart');
    }

    public function update_cart_quantity(Request $request)
    {
        $rowId = $request->rowId_cart;
        $qty = $request->cart_quantity;
        Cart::update($rowId, $qty);
        return Redirect::to('/show-cart');
    }

    public function add_cart_ajax(Request $request)
    {
        $data = $request->all();
        $session_id = substr(md5(microtime()), rand(0, 26), 5);
        $cart = Session::get('cart', []); // Luôn đảm bảo $cart là một mảng

        $is_avaiable = false;
        $existing_key = null; // Biến để lưu key của sản phẩm đã tồn tại

        // Kiểm tra xem sản phẩm đã tồn tại trong giỏ hàng chưa
        foreach ($cart as $key => $val) {
            if ($val['product_id'] == $data['cart_product_id']) {
                $is_avaiable = true;
                $existing_key = $key; // Lưu lại key của sản phẩm
                break;
            }
        }

        // Nếu sản phẩm đã tồn tại, chỉ cần cập nhật số lượng
        if ($is_avaiable) {
            $cart[$existing_key]['product_qty'] += $data['cart_product_qty'];
        }
        // Nếu sản phẩm chưa có, thêm mới với key là session_id
        else {
            // SỬA Ở ĐÂY: Dùng $session_id làm key của mảng
            $cart[$session_id] = [
                'session_id' => $session_id,
                'product_id' => $data['cart_product_id'],
                'product_name' => $data['cart_product_name'],
                'product_image' => $data['cart_product_image'],
                'product_price' => $data['cart_product_price'],
                'product_qty' => $data['cart_product_qty'],
            ];
        }

        Session::put('cart', $cart);
        Session::save();
    }

    public function gio_hang(Request $request)
    {
        $cate_product = Category::where('category_status', '1')->orderBy('category_id', 'desc')->get();
        $brand_product = Brand::where('brand_status', '1')->orderBy('brand_id', 'desc')->get();
        return view('pages.cart.cart_ajax')->with('category', $cate_product)->with('brand', $brand_product);
    }

    public function del_product($session_id)
    {
        $cart = Session::get('cart');
        if ($cart == true) {
            foreach ($cart as $key => $val) {
                if ($val['session_id'] == $session_id) {
                    unset($cart[$key]);
                }
            }
            Session::put('cart', $cart);
            return Redirect()->back()->with('message', 'Xóa sản phẩm thành công');
        } else {
            return Redirect()->back()->with('message', 'Xóa sản phẩm thất bại');
        }
    }

    public function update_cart(Request $request)
    {
        $data = $request->all();
        $cart = Session::get('cart');

        if ($cart) {
            $errors = []; // Mảng để lưu các lỗi

            // Vòng lặp 1: Chỉ để kiểm tra và thu thập lỗi
            foreach ($data['cart_qty'] as $session_id => $new_qty) {
                if (isset($cart[$session_id])) {
                    $product_id = $cart[$session_id]['product_id'];
                    $product = Product::find($product_id);

                    if ($product && $new_qty > $product->product_quantity) {
                        // Thêm lỗi vào mảng thay vì redirect ngay lập tức
                        $errors[] = 'Sản phẩm "' . $product->product_name . '" chỉ còn ' . $product->product_quantity . ' sản phẩm.';
                    }
                }
            }

            // Nếu có bất kỳ lỗi nào, redirect về với tất cả thông báo lỗi
            if (count($errors) > 0) {
                // Nối các thông báo lỗi lại với nhau
                $error_message = 'Cập nhật thất bại: ' . implode('<br>', $errors);
                return redirect()->back()->with('error', $error_message);
            }

            // Vòng lặp 2: Nếu không có lỗi nào, tiến hành cập nhật
            foreach ($data['cart_qty'] as $session_id => $new_qty) {
                if (isset($cart[$session_id])) {
                    $cart[$session_id]['product_qty'] = $new_qty;
                }
            }

            // Lưu giỏ hàng vào session và báo thành công
            Session::put('cart', $cart);
            return redirect()->back()->with('message', 'Cập nhật giỏ hàng thành công');
        } else {
            return redirect()->back()->with('error', 'Giỏ hàng của bạn đang trống.');
        }
    }

    public function del_all_product()
    {
        $cart = Session::get('cart');
        if ($cart == true) {
            Session::forget('cart');
            Session::forget('coupon');
            return Redirect()->back()->with('message', 'Xóa tất cả sản phẩm thành công');
        } else {
            return Redirect()->back()->with('message', 'Xóa tất cả sản phẩm thất bại');
        }
    }
}
