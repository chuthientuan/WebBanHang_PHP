<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;

class CategoryProductController extends Controller
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

    public function add_category_product()
    {
        $this->AuthLogin();
        return view('admin.add_category_product');
    }

    public function all_category_product()
    {
        $this->AuthLogin();
        $all_category_product = Category::orderBy('category_id', 'desc')->get();
        return view('admin.all_category_product')->with('all_category_product', $all_category_product);
    }

    public function save_category_product(Request $request)
    {
        $this->AuthLogin();
        $validatedData = $request->validate([
            'category_product_name' => 'required|string|max:255|unique:tbl_category_product,category_name',
            'category_product_desc' => 'required|string',
            'category_product_status' => 'required|boolean',
        ], [
            'category_product_name.required' => 'Tên danh mục không được để trống.',
            'category_product_name.unique' => 'Tên danh mục này đã tồn tại.',
            'category_product_desc.required' => 'Mô tả danh mục không được để trống.',
        ]);

        Category::create([
            'category_name' => $validatedData['category_product_name'],
            'category_desc' => $validatedData['category_product_desc'],
            'category_status' => $validatedData['category_product_status'],
        ]);

        Session::put('message', 'Thêm danh mục sản phẩm thành công');
        return Redirect::to('/add-category-product');
    }

    public function unactive_category_product($category_product_id)
    {
        $this->AuthLogin();
        $category = Category::find($category_product_id);
        if ($category) {
            $category->category_status = 1;
            $category->save();
            Session::put('message', 'Kích hoạt danh mục sản phẩm thành công');
        } else {
            Session::put('message', 'Không tìm thấy danh mục');
        }
        return Redirect::to('/all-category-product');
    }

    public function active_category_product($category_product_id)
    {
        $this->AuthLogin();
        $category = Category::find($category_product_id);
        if ($category) {
            $category->category_status = 0;
            $category->save();
            Session::put('message', 'Bỏ kích hoạt danh mục sản phẩm thành công');
        } else {
            Session::put('message', 'Không tìm thấy danh mục');
        }
        return Redirect::to('/all-category-product');
    }

    public function edit_category_product($category_product_id)
    {
        $this->AuthLogin();
        $edit_category_product = Category::findOrFail($category_product_id);
        return view('admin.edit_category_product')->with('edit_category_product', $edit_category_product);
    }

    public function update_category_product(Request $request, $category_product_id)
    {
        $this->AuthLogin();
        $validatedData = $request->validate([
            'category_product_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tbl_category_product', 'category_name')->ignore($category_product_id, 'category_id'),
            ],
            'category_product_desc' => 'required|string',
        ], [
            'category_product_name.required' => 'Tên danh mục không được để trống.',
            'category_product_name.unique' => 'Tên danh mục này đã tồn tại.',
            'category_product_desc.required' => 'Mô tả danh mục không được để trống.',
        ]);

        $category = Category::findOrFail($category_product_id);
        $category->update([
            'category_name' => $validatedData['category_product_name'],
            'category_desc' => $validatedData['category_product_desc'],
        ]);

        Session::put('message', 'Cập nhật danh mục sản phẩm thành công');
        return Redirect::to('/all-category-product');
    }

    public function delete_category_product($category_product_id)
    {
        $this->AuthLogin();
        $category = Category::find($category_product_id);
        if ($category) {
            $category->delete();
            Session::put('message', 'Xóa danh mục sản phẩm thành công');
        } else {
            Session::put('message', 'Xóa danh mục thất bại');
        }
        return Redirect::to('/all-category-product');
    }

    //End function admin pages

    public function show_category_home(Request $request, $category_id)
    {
        $cate_product = Category::where('category_status', '1')->orderBy('category_id', 'desc')->get();
        $brand_product = Brand::where('brand_status', '1')->orderBy('brand_id', 'desc')->get();

        $category = Category::findOrFail($category_id);

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

        $productsQuery = $category->products()->where('product_status', '1');

        // Áp dụng bộ lọc giá
        if ($min_price !== null && $max_price !== null) {
            $productsQuery->whereBetween('product_price', [$min_price, $max_price]);
        } elseif ($min_price !== null) { // Chỉ có giá tối thiểu (ví dụ: Trên 20 triệu)
            $productsQuery->where('product_price', '>=', $min_price);
        } elseif ($max_price !== null) { // Chỉ có giá tối đa (ví dụ: Dưới 10 triệu)
            $productsQuery->where('product_price', '<=', $max_price);
        }

        $products_by_category = $productsQuery->paginate(6);

        return view('pages.category.show_category')
            ->with('category', $cate_product)
            ->with('brand', $brand_product)
            ->with('category_by_id', $products_by_category)
            ->with('category_name', $category->category_name)
            ->with('selected_price_range', $price_range);
    }
}
