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
                <li><a href="#companyprofile" data-toggle="tab">Chi tiết sản phẩm </a></li>
                <li><a href="#reviews" data-toggle="tab">Đánh giá</a></li>
            </ul>
        </div>
        <div class="tab-content">
            <div class="tab-pane fade active in" id="details">
                <p>{!! $product_details->product_desc !!}</p>
            </div>

            <div class="tab-pane fade" id="companyprofile">
                <p>{!! $product_details->product_content !!}</p>
            </div>

            <div class="tab-pane fade" id="reviews">
                <div class="col-sm-12">
                    <ul>
                        <li><a href=""><i class="fa fa-user"></i>EUGEN</a></li>
                        <li><a href=""><i class="fa fa-clock-o"></i>12:41 PM</a></li>
                        <li><a href=""><i class="fa fa-calendar-o"></i>31 DEC 2014</a></li>
                    </ul>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut
                        labore
                        et dolore magna aliqua.Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi
                        ut
                        aliquip ex ea commodo consequat.Duis aute irure dolor in reprehenderit in voluptate velit esse
                        cillum dolore eu fugiat nulla pariatur.</p>
                    <p><b>Write Your Review</b></p>

                    <form action="#">
                        <span>
                            <input type="text" placeholder="Your Name" />
                            <input type="email" placeholder="Email Address" />
                        </span>
                        <textarea name=""></textarea>
                        <b>Rating: </b> <img src="images/product-details/rating.png" alt="" />
                        <button type="button" class="btn btn-default pull-right">
                            Submit
                        </button>
                    </form>
                </div>
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
                                        <img src="{{ URL::to('public/Uploads/product/' . $lienquan->product_image) }}"
                                            alt="" />
                                        <h2>{{ number_format($lienquan->product_price) . ' ' . 'VNĐ' }}</h2>
                                        <p>{{ $lienquan->product_name }}</p>
                                        <a href="#" class="btn btn-default add-to-cart"><i
                                                class="fa fa-shopping-cart"></i>
                                            Thêm vào giỏ hàng</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
            <a class="left recommended-item-control" href="#recommended-item-carousel" data-slide="prev">
                <i class="fa fa-angle-left"></i>
            </a>
            <a class="right recommended-item-control" href="#recommended-item-carousel" data-slide="next">
                <i class="fa fa-angle-right"></i>
            </a>
        </div>
    </div>
@endsection
