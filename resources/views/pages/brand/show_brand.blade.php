@extends('index')
@section('sidebar')
    @include('pages.include.sidebar')
@endsection
@section('slider')
    @include('pages.include.slider')
@endsection
@section('content')
    <div class="features_items"><!--features_items-->
        <h2 class="title text-center">{{ $brand_name }}</h2>
        @foreach ($brand_by_id as $key => $product)
            <a href="{{ URL::to('/chi-tiet-san-pham/' . $product->product_id) }}">
                <div class="col-sm-4">
                    <div class="product-image-wrapper">
                        <div class="single-products">
                            <div class="productinfo text-center">
                                <form>
                                    @csrf
                                    <input type="hidden" name="" value="{{ $product->product_id }}"
                                        class="cart_product_id_{{ $product->product_id }}">
                                    <input type="hidden" name="" value="{{ $product->product_name }}"
                                        class="cart_product_name_{{ $product->product_id }}">
                                    <input type="hidden" name="" value="{{ $product->product_image }}"
                                        class="cart_product_image_{{ $product->product_id }}">
                                    <input type="hidden" name="" value="{{ $product->product_price }}"
                                        class="cart_product_price_{{ $product->product_id }}">
                                    <input type="hidden" name="" value="1"
                                        class="cart_product_qty_{{ $product->product_id }}">
                                    <a href="{{ URL::to('/chi-tiet-san-pham/' . $product->product_id) }}">
                                        <img src="{{ URL::to('public/Uploads/product/' . $product->product_image) }}"
                                            alt="" />
                                        <h2>{{ number_format($product->product_price) . ' ' . 'VNĐ' }}</h2>
                                        <p>{{ $product->product_name }}</p>

                                    </a>
                                    <button type="button" class="btn btn-default add-to-cart" name="add-to-cart"
                                        data-id="{{ $product->product_id }}"> Thêm vào giỏ hàng </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div><!--features_items-->
@endsection
