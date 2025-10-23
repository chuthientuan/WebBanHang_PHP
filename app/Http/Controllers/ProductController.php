<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
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
        $validatedData = $request->validate(
            [
                'product_name' => 'required|string|max:255',
                'product_price' => 'required|numeric|min:1',
                'product_desc' => 'required|string',
                'product_content' => 'required|string',
                'product_cate' => [
                    'required',
                    'integer',
                    Rule::exists('tbl_category_product', 'category_id')
                ],
                'product_brand' => [
                    'required',
                    'integer',
                    Rule::exists('tbl_brand', 'brand_id')
                ],
                'product_quantity' => 'required|integer|min:1',
                'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ],
            [
                'product_name.required' => 'Tên sản phẩm không được để trống.',
                'product_price.required' => 'Giá sản phẩm không được để trống.',
                'product_price.numeric' => 'Giá sản phẩm phải là một số.',
                'product_desc.required' => 'Mô tả sản phẩm không được để trống.',
                'product_content.required' => 'Nội dung sản phẩm không được để trống.',
                'product_cate.required' => 'Vui lòng chọn danh mục sản phẩm.',
                'product_cate.exists' => 'Danh mục sản phẩm không hợp lệ.',
                'product_brand.required' => 'Vui lòng chọn thương hiệu sản phẩm.',
                'product_brand.exists' => 'Thương hiệu sản phẩm không hợp lệ.',
                'product_quantity.required' => 'Số lượng sản phẩm không được để trống.',
                'product_quantity.integer' => 'Số lượng sản phẩm phải là số nguyên.',
                'product_image.image' => 'File tải lên phải là hình ảnh.',
                'product_image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif.',
                'product_image.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
            ]
        );

        $data = [
            'product_name' => $validatedData['product_name'],
            'product_price' => $validatedData['product_price'],
            'product_desc' => $validatedData['product_desc'],
            'product_content' => $validatedData['product_content'],
            'category_id' => $validatedData['product_cate'],
            'brand_id' => $validatedData['product_brand'],
            'product_status' => $validatedData['product_status'],
            'product_quantity' => $validatedData['product_quantity'],
            'product_image' => '',
            'product_sold' => 0
        ];
        if ($request->hasFile('product_image')) {
            if ($request->file('product_image')->isValid()) {
                $get_image = $request->file('product_image');
                $new_image_name = time() . '_' . $get_image->getClientOriginalName();
                $get_image->move(public_path('Uploads/product'), $new_image_name);
                $data['product_image'] = $new_image_name;
            } else {
                return Redirect::back()->withErrors(['product_image' => 'File hình ảnh không hợp lệ.'])->withInput();
            }
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
        $validatedData = $request->validate(
            [
                'product_name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('tbl_product', 'product_name')->ignore($product_id, 'product_id')
                ],
                'product_price' => 'required|numeric|min:0',
                'product_desc' => 'required|string',
                'product_content' => 'required|string',
                'product_cate' => [
                    'required',
                    'integer',
                    Rule::exists('tbl_category_product', 'category_id')
                ],
                'product_brand' => [
                    'required',
                    'integer',
                    Rule::exists('tbl_brand', 'brand_id')
                ],
                'product_status' => 'required|boolean',
                'product_quantity' => 'required|integer|min:0',
                'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ],
            [
                'product_name.required' => 'Tên sản phẩm không được để trống.',
                'product_name.unique' => 'Tên sản phẩm này đã tồn tại.', 
                'product_price.required' => 'Giá sản phẩm không được để trống.',
                'product_price.numeric' => 'Giá sản phẩm phải là một số.',
                'product_desc.required' => 'Mô tả sản phẩm không được để trống.',
                'product_content.required' => 'Nội dung sản phẩm không được để trống.',
                'product_cate.required' => 'Vui lòng chọn danh mục sản phẩm.',
                'product_cate.exists' => 'Danh mục sản phẩm không hợp lệ.',
                'product_brand.required' => 'Vui lòng chọn thương hiệu sản phẩm.',
                'product_brand.exists' => 'Thương hiệu sản phẩm không hợp lệ.',
                'product_quantity.required' => 'Số lượng sản phẩm không được để trống.',
                'product_quantity.integer' => 'Số lượng sản phẩm phải là số nguyên.',
                'product_image.image' => 'File tải lên phải là hình ảnh.',
                'product_image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif.',
                'product_image.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
            ]
        );

        $data = [
            'product_name' => $validatedData['product_name'],
            'product_price' => $validatedData['product_price'],
            'product_desc' => $validatedData['product_desc'],
            'product_content' => $validatedData['product_content'],
            'category_id' => $validatedData['product_cate'],
            'brand_id' => $validatedData['product_brand'],
            'product_status' => $validatedData['product_status'],
            'product_quantity' => $validatedData['product_quantity'],
        ];

        if ($request->hasFile('product_image')) {
            if ($request->file('product_image')->isValid()) {
                $get_image = $request->file('product_image');

                $new_image_name = time() . '_' . $get_image->getClientOriginalName();
                $get_image->move(public_path('Uploads/product'), $new_image_name);

                $data['product_image'] = $new_image_name;

            } else {
                return Redirect::back()->withErrors(['product_image' => 'File hình ảnh không hợp lệ.'])->withInput();
            }
        }
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
