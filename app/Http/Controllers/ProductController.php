<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class ProductController extends Controller
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

    public function add_product()
    {
        $this->AuthLogin();
        $cate_product = Category::orderBy('category_id', 'desc')->get();
        $brand_product = Brand::orderBy('brand_id', 'desc')->get();
        return view('admin.add_product')->with('cate_product', $cate_product)->with('brand_product', $brand_product);
    }

    public function all_product()
    {
        $this->AuthLogin();
        $all_product = Product::with('category', 'brand')
            ->orderBy('product_id', 'desc')
            ->get();
        return view('admin.all_product')->with('all_product', $all_product);
    }

    public function save_product(Request $request)
    {
        $this->AuthLogin();
        $data = [
            'product_name' => $request->product_name,
            'product_price' => $request->product_price,
            'product_desc' => $request->product_desc,
            'product_content' => $request->product_content,
            'category_id' => $request->product_cate,
            'brand_id' => $request->product_brand,
            'product_status' => $request->product_status,
            'product_quantity' => $request->product_quantity,
            'product_image' => '',
            'product_sold' => 0
        ];
        if ($request->hasFile('product_image')) {
            $get_image = $request->file('product_image');
            $new_image_name = time() . '_' . $get_image->getClientOriginalName();
            $get_image->move(public_path('Uploads/product'), $new_image_name);

            $data['product_image'] = $new_image_name;
        }
        Product::create($data);
        Session::put('message', 'Thêm sản phẩm thành công');
        return Redirect::to('all-product');
    }

    public function unactive_product($product_id)
    {
        $this->AuthLogin();
        $product = Product::find($product_id);
        if ($product) {
            $product->product_status = 1;
            $product->save();
            Session::put('message', 'Kích hoạt danh mục sản phẩm thành công');
        } else {
            Session::put('message', 'Không tìm thấy danh mục');
        }
        return Redirect::to('all-product');
    }

    public function active_product($product_id)
    {
        $this->AuthLogin();
        $product = Product::find($product_id);
        if ($product) {
            $product->product_status = 0;
            $product->save();
            Session::put('message', 'Bỏ kích hoạt danh mục sản phẩm thành công');
        } else {
            Session::put('message', 'Không tìm thấy danh mục');
        }
        return Redirect::to('all-product');
    }

    public function edit_product($product_id)
    {
        $this->AuthLogin();
        $cate_product = Category::orderBy('category_id', 'desc')->get();
        $brand_product = Brand::orderBy('brand_id', 'desc')->get();

        $edit_product = Product::where('product_id', $product_id)->get();

        $manager_product = view('admin.edit_product')
            ->with('edit_product', $edit_product)
            ->with('cate_product', $cate_product)
            ->with('brand_product', $brand_product);
        return view('admin_layout')->with('admin.edit_product', $manager_product);
    }

    public function update_product(Request $request, $product_id)
    {
        $this->AuthLogin();
        $data = [
            'product_name' => $request->product_name,
            'product_price' => $request->product_price,
            'product_desc' => $request->product_desc,
            'product_content' => $request->product_content,
            'category_id' => $request->product_cate,
            'brand_id' => $request->product_brand,
            'product_status' => $request->product_status,
            'product_quantity' => $request->product_quantity,
        ];

        $get_image = $request->file('product_image');
        if ($get_image) {
            $get_name_image = $get_image->getClientOriginalName();
            $name_image = current(explode('.', $get_name_image));
            $new_image = $name_image . rand(0, 99) . '.' . $get_image->getClientOriginalExtension();
            // Note: The original path 'public/Uploads/product' is relative to the public directory.
            $get_image->move('public/Uploads/product', $new_image);
            $data['product_image'] = $new_image;
        }

        // Use Eloquent Model for update
        Product::where('product_id', $product_id)->update($data);

        Session::put('message', 'Cập nhật sản phẩm thành công');
        return Redirect::to('all-product');
    }

    public function delete_product($product_id)
    {
        $this->AuthLogin();
        Product::destroy($product_id);
        Session::put('message', 'Xóa sản phẩm thành công');
        return Redirect::to('/all-product');
    }

    //End Admin Page
    public function details_product($product_id)
    {
        $cate_product = Category::where('category_status', '1')->orderBy('category_id', 'desc')->get();
        $brand_product = Brand::where('brand_status', '1')->orderBy('brand_id', 'desc')->get();

        $details_product = Product::with('category', 'brand')->find($product_id);

        if (!$details_product) {
            $category_id = null;
        } else {
            $category_id = $details_product->category_id;
        }

        $related_product = collect();
        if ($category_id) {
            $related_product = Product::with(['category', 'brand'])
                ->where('category_id', $category_id)
                ->where('product_id', '!=', $product_id)
                ->get();
        }

        return view('pages.product.show_details')
            ->with('category', $cate_product)
            ->with('brand', $brand_product)
            ->with('product_details', $details_product)
            ->with('relate', $related_product);
    }
}
