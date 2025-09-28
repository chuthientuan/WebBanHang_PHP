<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Feeship;
use App\Models\Province;
use App\Models\Ward;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function delivery(Request $request)
    {
        $city = City::orderby('matp', 'ASC')->get();

        return view('admin.delivery.add_delivery')->with(compact('city'));
    }

    public function select_delivery(Request $request)
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

    public function insert_delivery(Request $request)
    {
        $data = $request->all();
        $fee_ship = new Feeship();
        $fee_ship->fee_matp = $data['city'];
        $fee_ship->fee_maqh = $data['province'];
        $fee_ship->fee_xaid = $data['wards'];
        $fee_ship->fee_feeship = $data['fee_ship'];
        $fee_ship->save();
    }
}
