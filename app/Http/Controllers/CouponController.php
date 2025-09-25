<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class CouponController extends Controller
{
    public function check_coupon(Request $request)
    {
        $data = $request->all();
        $coupon = Coupon::where('coupon_code', $data['coupon'])->first();
        if ($coupon) {
            $count_coupon = $coupon->count();
            if ($count_coupon > 0) {
                $coupon_session = Session::get('coupon');
                if ($coupon_session == true) {
                    $is_avaiable = 0;
                    if ($is_avaiable == 0) {
                        $cou[] = array(
                            'coupon_code' => $coupon->coupon_code,
                            'coupon_condition' => $coupon->coupon_condition,
                            'coupon_number' => $coupon->coupon_number,
                        );
                        Session::put('coupon', $cou);
                    }
                } else {
                    $cou[] = array(
                        'coupon_code' => $coupon->coupon_code,
                        'coupon_condition' => $coupon->coupon_condition,
                        'coupon_number' => $coupon->coupon_number,
                    );
                    Session::put('coupon', $cou);
                }
                Session::save();
                return redirect()->back()->with('message', 'Thêm mã giảm giá thành công');
            }
        } else {
            return redirect()->back()->with('error', 'Mã giảm giá không đúng hoặc đã hết hạn.');
        }
    }

    public function insert_coupon(Request $request)
    {
        return view('admin.coupon.insert_coupon');
    }

    public function insert_coupon_code(Request $request)
    {
        $data = $request->all();
        $coupon = new Coupon;
        $coupon->coupon_name = $data['coupon_name'];
        $coupon->coupon_code = $data['coupon_code'];
        $coupon->coupon_time = $data['coupon_time'];
        $coupon->coupon_condition = $data['coupon_condition'];
        $coupon->coupon_number = $data['coupon_number'];
        $coupon->save();

        $request->session()->put('message', 'Thêm mã giảm giá thành công');
        return Redirect::to('/insert-coupon');
    }

    public function list_coupon()
    {
        $coupon = Coupon::orderby('coupon_id', 'DESC')->get();
        return view('admin.coupon.list_coupon')->with(compact('coupon'));
    }

    public function delete_coupon($coupon_id)
    {
        $coupon = Coupon::find($coupon_id);
        $coupon->delete();
        Session::put('message', 'Xóa mã giảm giá thành công');
        return Redirect::to('/list-coupon');
    }

    public function unset_coupon()
    {
        $coupon = Session::get('coupon');
        if ($coupon == true) {
            Session::forget('coupon');
            return Redirect()->back()->with('message', 'Xóa mã khuyến mãi thành công');
        } else {
            return Redirect()->back()->with('message', 'Xóa mã khuyến mãi thất bại');
        }
    }
}
