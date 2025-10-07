<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class BrandProductController extends Controller
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

    public function add_brand_product()
    {
        $this->AuthLogin();
        return view('admin.add_brand_product');
    }

    public function all_brand_product()
    {
        $this->AuthLogin();
        // $all_brand_product = DB::table('tbl_brand')->get();
        $all_brand_product = Brand::all();
        $manager_brand_product = view('admin.all_brand_product')->with('all_brand_product', $all_brand_product);
        return view('admin_layout')->with('admin.all_brand_product', $manager_brand_product);
    }

    public function save_brand_product(Request $request)
    {
        $this->AuthLogin();
        $brand = new Brand();
        $brand->brand_name = $request->brand_product_name;
        $brand->brand_desc = $request->brand_product_desc;
        $brand->brand_status = $request->brand_product_status;
        $brand->save();
        Session::put('message', 'Thêm thương hiệu  sản phẩm thành công');
        return Redirect::to('add-brand-product');
    }

    public function unactive_brand_product($brand_product_id)
    {
        $this->AuthLogin();
        $brand = Brand::find($brand_product_id);
        if ($brand) {
            $brand->brand_status = 1;
            $brand->save();
            Session::put('message', 'Kích hoạt thương hiệu sản phẩm thành công');
        } else {
            Session::put('message', 'Không tìm thấy thương hiệu');
        }
        return Redirect::to('/all-brand-product');
    }

    public function active_brand_product($brand_product_id)
    {
        $this->AuthLogin();
        $brand = Brand::find($brand_product_id);
        if ($brand) {
            $brand->brand_status = 0;
            $brand->save();
            Session::put('message', 'Bỏ kích hoạt thương hiệu sản phẩm thành công');
        } else {
            Session::put('message', 'Không tìm thấy thương hiệu');
        }
        return Redirect::to('/all-brand-product');
    }

    public function edit_brand_product($brand_product_id)
    {
        $this->AuthLogin();
        $edit_brand_product = Brand::find($brand_product_id);
        return view('admin.edit_brand_product')->with('edit_brand_product', $edit_brand_product);
    }

    public function update_brand_product(Request $request, $brand_product_id)
    {
        $this->AuthLogin();
        $brand = Brand::find($brand_product_id);
        if ($brand) {
            $brand->brand_name = $request->brand_product_name;
            $brand->brand_desc = $request->brand_product_desc;
            $brand->save();
            Session::put('message', 'Cập nhật thương hiệu sản phẩm thành công');
        } else {
            Session::put('message', 'Cập nhật thương hiệu thất bại');
        }

        return Redirect::to('/all-brand-product');
    }

    public function delete_brand_product($brand_product_id)
    {
        $this->AuthLogin();
        $brand = Brand::find($brand_product_id);
        if ($brand) {
            $brand->delete();
            Session::put('message', 'Xóa thương hiệu sản phẩm thành công');
        } else {
            Session::put('message', 'Xóa thương hiệu thất bại');
        }

        return Redirect::to('/all-brand-product');
    }

    //end function admin page
    public function show_brand_home($brand_id)
    {
        $cate_product = Category::where('category_status', '1')->orderBy('category_id', 'desc')->get();
        $brand_product = Brand::where('brand_status', '1')->orderBy('brand_id', 'desc')->get();

        $current_brand = Brand::findOrFail($brand_id);

        $brand_by_id = $current_brand->products()->where('product_status', '1')->get();

        return view('pages.brand.show_brand')
            ->with('category', $cate_product)
            ->with('brand', $brand_product)
            ->with('brand_by_id', $brand_by_id)
            ->with('brand_name', $current_brand->brand_name);
    }
}
