@extends('index')
@section('sidebar')
    @include('pages.include.sidebar')
@endsection
@section('content')
    <div class="product-details"><!--product-details-->
        <div class="col-sm-5">
            <div class="view-product">
                <img src="{{ asset('public/Uploads/product/' . $product_details->product_image) }}" alt="" />
            </div>
        </div>
        <div class="col-sm-7">
            <div class="product-information"><!--/product-information-->
                <img src="images/product-details/new.jpg" class="newarrival" alt="" />
                <h2>{{ $product_details->product_name }}</h2>
                <p> Số lượng: {{ $product_details->product_quantity }}</p>
                <img src="images/product-details/rating.png" alt="" />
                <form>
                    {{ csrf_field() }}
                    <span>
                        <span>{{ number_format($product_details->product_price) . ' VNĐ' }}</span>
                        <label>Số lượng:</label>
                        <input name="qty" type="number" min="1" value="1"
                            max="{{ $product_details->product_quantity }}" />
                        <input name="productid_hidden" type="hidden" value="{{ $product_details->product_id }}" />

                        {{-- Đổi type="submit" thành type="button" và thêm class/id để JS bắt sự kiện --}}
                        <button type="button" class="btn btn-fefault cart add-to-cart-detail">
                            <i class="fa fa-shopping-cart"></i>
                            Thêm vào giỏ hàng
                        </button>
                    </span>
                </form>
                <p><b>Tình Trạng:</b> còn hàng </p>
                <p><b>Điều Kiện :</b> Mới 100%</p>
                <p><b>Thương Hiệu :</b> {{ $product_details->brand->brand_name }}</p>
                <p><b>Danh Mục :</b> {{ $product_details->category->category_name }}</p>
                <a href=""><img src="images/product-details/share.png" class="share img-responsive"
                        alt="" /></a>
            </div><!--/product-information-->
        </div>
    </div><!--/product-details-->

    <div class="category-tab shop-details-tab"><!--category-tab-->
        <div class="col-sm-12">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#details" data-toggle="tab">Mô tả sản phẩm </a></li>
            </ul>
        </div>
        <div class="tab-content">
            <div class="tab-pane fade active in" id="details">
                <p>{!! $product_details->product_desc !!}</p>
            </div>
        </div>
    </div><!--/category-tab-->
    <div class="recommended_items"><!--recommended_items-->
        <h2 class="title text-center">Sản phẩm liên quan </h2>

        <div id="recommended-item-carousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <div class="item active">
                    @foreach ($relate as $key => $lienquan)
                        <div class="col-sm-4">
                            <div class="product-image-wrapper">
                                <div class="single-products">
                                    <div class="productinfo text-center">
                                        <form action="">
                                            @csrf
                                            <input type="hidden" name="" value="{{ $lienquan->product_id }}"
                                                class="cart_product_id_{{ $lienquan->product_id }}">
                                            <input type="hidden" name="" value="{{ $lienquan->product_name }}"
                                                class="cart_product_name_{{ $lienquan->product_id }}">
                                            <input type="hidden" name="" value="{{ $lienquan->product_image }}"
                                                class="cart_product_image_{{ $lienquan->product_id }}">
                                            <input type="hidden" name="" value="{{ $lienquan->product_price }}"
                                                class="cart_product_price_{{ $lienquan->product_id }}">
                                            <input type="hidden" name="" value="1"
                                                class="cart_product_qty_{{ $lienquan->product_id }}">
                                            <a href="{{ URL::to('/chi-tiet-san-pham/' . $lienquan->product_id) }}">
                                                <img src="{{ URL::to('public/Uploads/product/' . $lienquan->product_image) }}"
                                                    alt="" />
                                                <h2>{{ number_format($lienquan->product_price) . ' ' . 'VNĐ' }}</h2>
                                                <p>{{ $lienquan->product_name }}</p>
                                            </a>
                                            <button type="button" class="btn btn-default add-to-cart" name="add-to-cart"
                                                data-id="{{ $lienquan->product_id }}"> Thêm vào giỏ hàng </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
            
        </div>
    </div>
@endsection
