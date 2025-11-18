<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $cate_product = Category::where('category_status', '1')->orderBy('category_id', 'desc')->get();
        $brand_product = Brand::where('brand_status', '1')->orderBy('brand_id', 'desc')->get();

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

        $productsQuery = Product::where('product_status', '1');

        if ($min_price !== null && $max_price !== null) {
            $productsQuery->whereBetween('product_price', [$min_price, $max_price]);
        } elseif ($min_price !== null) { 
            $productsQuery->where('product_price', '>=', $min_price);
        } elseif ($max_price !== null) { 
            $productsQuery->where('product_price', '<=', $max_price);
        }

        $all_product = $productsQuery->orderBy('product_id', 'desc')->paginate(6);

        return view('pages.home')
            ->with('category', $cate_product)
            ->with('brand', $brand_product)
            ->with('all_product', $all_product)
            ->with('selected_price_range', $price_range);
    }

    public function search(Request $request)
    {
        $keywords = $request->keywords_submit;
        $cate_product = Category::where('category_status', '1')->orderBy('category_id', 'desc')->get();
        $brand_product = Brand::where('brand_status', '1')->orderBy('brand_id', 'desc')->get();
        $search_product = Product::where('product_name', 'like', '%' . $keywords . '%')->get();
        return view('pages.product.search')->with('category', $cate_product)->with('brand', $brand_product)->with('search_product', $search_product);
    }
}
