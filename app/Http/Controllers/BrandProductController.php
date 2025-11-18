<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;

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
        $all_brand_product = Brand::orderBy('brand_id', 'desc')->get();
        return view('admin.all_brand_product')->with('all_brand_product', $all_brand_product);
    }

    public function save_brand_product(Request $request)
    {
        $this->AuthLogin();
        $validatedData = $request->validate([
            'brand_product_name' => 'required|string|max:255|unique:tbl_brand,brand_name',
            'brand_product_desc' => 'required|string',
            'brand_product_status' => 'required|boolean',
        ], [
            'brand_product_name.required' => 'Tên thương hiệu không được để trống.',
            'brand_product_name.unique' => 'Tên thương hiệu này đã tồn tại.',
            'brand_product_desc.required' => 'Mô tả thương hiệu không được để trống.',
        ]);
        Brand::create([
            'brand_name' => $validatedData['brand_product_name'],
            'brand_desc' => $validatedData['brand_product_desc'],
            'brand_status' => $validatedData['brand_product_status'],
        ]);

        Session::put('message', 'Thêm thương hiệu sản phẩm thành công');
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
        $edit_brand_product = Brand::findOrFail($brand_product_id);
        return view('admin.edit_brand_product')->with('edit_brand_product', $edit_brand_product);
    }

    public function update_brand_product(Request $request, $brand_product_id)
    {
        $this->AuthLogin();
        $validatedData = $request->validate([
            'brand_product_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tbl_brand', 'brand_name')->ignore($brand_product_id, 'brand_id'),
            ],
            'brand_product_desc' => 'required|string',
        ], [
            'brand_product_name.required' => 'Tên thương hiệu không được để trống.',
            'brand_product_name.unique' => 'Tên thương hiệu này đã tồn tại.',
            'brand_product_desc.required' => 'Mô tả thương hiệu không được để trống.',
        ]);

        $brand = Brand::findOrFail($brand_product_id);
        $brand->update([
            'brand_name' => $validatedData['brand_product_name'],
            'brand_desc' => $validatedData['brand_product_desc'],
        ]);

        Session::put('message', 'Cập nhật thương hiệu sản phẩm thành công');
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
    public function show_brand_home(Request $request, $brand_id)
    {
        $cate_product = Category::where('category_status', '1')->orderBy('category_id', 'desc')->get();
        $brand_product = Brand::where('brand_status', '1')->orderBy('brand_id', 'desc')->get();

        $current_brand = Brand::findOrFail($brand_id);

        $price_range = $request->input('price_range');
        $min_price = null;
        $max_price = null;

        if ($price_range) {
            $parts = explode('-', $price_range);
            if (isset($parts[0]) && is_numeric($parts[0])) {
                $min_price = (int)$parts[0];
            }
            if (isset($parts[1]) && is_numeric($parts[1])) {
                $max_price = (int)$parts[1];
            }
        }

        $productsQuery = $current_brand->products()->where('product_status', '1');

        // Áp dụng bộ lọc giá
        if ($min_price !== null && $max_price !== null) {
            $productsQuery->whereBetween('product_price', [$min_price, $max_price]);
        } elseif ($min_price !== null) { // Chỉ có giá tối thiểu (ví dụ: Trên 20 triệu)
            $productsQuery->where('product_price', '>=', $min_price);
        } elseif ($max_price !== null) { // Chỉ có giá tối đa (ví dụ: Dưới 10 triệu)
            $productsQuery->where('product_price', '<=', $max_price);
        }

        $products_by_brand = $productsQuery->paginate(6);

        return view('pages.brand.show_brand')
            ->with('category', $cate_product)
            ->with('brand', $brand_product)
            ->with('brand_by_id', $products_by_brand)
            ->with('brand_name', $current_brand->brand_name)
            ->with('selected_price_range', $price_range);;
    }
}
