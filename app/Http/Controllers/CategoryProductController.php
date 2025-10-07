<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

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
        $all_category_product = Category::get();
        $manager_category_product = view('admin.all_category_product')->with('all_category_product', $all_category_product);
        return view('admin_layout')->with('admin.all_category_product', $manager_category_product);
    }

    public function save_category_product(Request $request)
    {
        $this->AuthLogin();
        $category = new Category();
        $category->category_name = $request->category_product_name;
        $category->category_desc = $request->category_product_desc;
        $category->category_status = $request->category_product_status;
        $category->save();
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
        $edit_category_product = Category::find($category_product_id);
        return view('admin.edit_category_product')->with('edit_category_product', $edit_category_product);
    }

    public function update_category_product(Request $request, $category_product_id)
    {
        $this->AuthLogin();
        $category = Category::find($category_product_id);
        if ($category) {
            $category->category_name = $request->category_product_name;
            $category->category_desc = $request->category_product_desc;
            $category->save();
            Session::put('message', 'Cập nhật danh mục sản phẩm thành công');
        } else {
            Session::put('message', 'Cập nhật danh mục thất bại');
        }
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

    public function show_category_home($category_id)
    {
        $cate_product = Category::where('category_status', '1')->orderBy('category_id', 'desc')->get();
        $brand_product = Brand::where('brand_status', '1')->orderBy('brand_id', 'desc')->get();

        $category = Category::findOrFail($category_id);

        $products_by_category = $category->products()->where('product_status', '1')->get();

        return view('pages.category.show_category')
            ->with('category', $cate_product)
            ->with('brand', $brand_product)
            ->with('category_by_id', $products_by_category)
            ->with('category_name', $category->category_name);
    }
}
