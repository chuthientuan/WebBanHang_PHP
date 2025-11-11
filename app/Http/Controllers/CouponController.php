<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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

    public function insert_coupon()
    {
        $generated_code = strtoupper(Str::random(8));
        while (Coupon::where('coupon_code', $generated_code)->exists()) {
            $generated_code = strtoupper(Str::random(8));
        }

        return view('admin.coupon.insert_coupon')->with('generated_code', $generated_code);
    }

    public function insert_coupon_code(Request $request)
    {
        $validatedData = $request->validate([
            'coupon_name' => 'required|string|max:255|unique:tbl_coupon,coupon_name',
            'generated_coupon_code' => 'required|string|unique:tbl_coupon,coupon_code',
            'coupon_time' => 'required|integer|min:1',
            'coupon_condition' => 'required|integer|in:1,2',
            'coupon_number' => [
                'required',
                'numeric',
                'min:1',
                // Thêm quy tắc: nếu giảm theo % (condition=1), thì max là 100
                Rule::when($request->input('coupon_condition') == 1, ['max:100']),
            ],
        ], [
            // Thông báo lỗi tùy chỉnh bằng tiếng Việt
            'coupon_name.required' => 'Tên mã giảm giá không được để trống.',
            'coupon_name.unique' => 'Tên mã này đã tồn tại, vui lòng chọn tên khác.',
            'generated_coupon_code.unique' => 'Mã giảm giá này đã được sử dụng. Vui lòng tải lại trang để lấy mã mới.',
            'coupon_time.required' => 'Số lượng mã không được để trống.',
            'coupon_time.integer' => 'Số lượng mã phải là số nguyên.',
            'coupon_time.min' => 'Số lượng mã phải ít nhất là 1.',
            'coupon_condition.in' => 'Vui lòng chọn tính năng mã hợp lệ.',
            'coupon_number.required' => 'Vui lòng nhập số % hoặc số tiền giảm.',
            'coupon_number.numeric' => 'Giá trị giảm phải là một con số.',
            'coupon_number.min' => 'Giá trị giảm phải lớn hơn 0.',
            'coupon_number.max' => 'Giá trị giảm theo % không được vượt quá 100.',
        ]);
        // --- KẾT THÚC PHẦN VALIDATION ---

        $coupon = new Coupon;
        // Sử dụng dữ liệu đã được validate
        $coupon->coupon_name = $validatedData['coupon_name'];
        $coupon->coupon_code = $validatedData['generated_coupon_code'];
        $coupon->coupon_time = $validatedData['coupon_time'];
        $coupon->coupon_condition = $validatedData['coupon_condition'];
        $coupon->coupon_number = $validatedData['coupon_number'];
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
