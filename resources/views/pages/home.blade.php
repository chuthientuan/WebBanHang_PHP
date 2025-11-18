@extends('index')
@section('sidebar')
    @include('pages.include.sidebar')
@endsection
@section('slider')
    @include('pages.include.slider')
@endsection
@section('content')
    <div class="features_items"><!--features_items-->
        <h2 class="title text-center">Sản phẩm mới nhất</h2>
        <div class="row" style="margin-bottom: 20px; padding: 0 15px;">
            <div class="col-sm-12">
                <form method="GET" action="{{ URL::current() }}" class="form-inline pull-right">
                    <label style="margin-right: 10px;">Lọc theo giá:</label>
                    <div class="form-group" style="margin-right: 10px;">
                        <select name="price_range" class="form-control" onchange="this.form.submit()">
                            <option value="">Tất cả mức giá</option>
                            <option value="0-10000000"
                                {{ ($selected_price_range ?? '') == '0-10000000' ? 'selected' : '' }}>
                                Dưới 10 triệu
                            </option>
                            <option value="10000000-15000000"
                                {{ ($selected_price_range ?? '') == '10000000-15000000' ? 'selected' : '' }}>
                                Từ 10 - 15 triệu
                            </option>
                            <option value="15000000-20000000"
                                {{ ($selected_price_range ?? '') == '15000000-20000000' ? 'selected' : '' }}>
                                Từ 15 - 20 triệu
                            </option>
                            <option value="20000000-25000000"
                                {{ ($selected_price_range ?? '') == '20000000-25000000' ? 'selected' : '' }}>
                                Từ 20 - 25 triệu
                            </option>
                            <option value="25000000" {{ ($selected_price_range ?? '') == '25000000' ? 'selected' : '' }}>
                                Trên 25 triệu
                            </option>
                        </select>
                    </div>
                    {{-- Nút Reset không còn cần thiết nếu dùng onchange --}}
                    <a href="{{ URL::current() }}" class="btn btn-secondary" style="margin-left: 5px;">Reset</a>
                </form>
            </div>

            @if (isset($selected_price_range) && $selected_price_range != '')
                <div class="col-sm-12 text-info" style="margin-top: 10px;">
                    @php
                        $range_text = '';
                        $parts = explode('-', $selected_price_range);
                        if (count($parts) == 2) {
                            if ($parts[0] == 0) {
                                $range_text = 'dưới ' . number_format($parts[1]) . 'đ';
                            } else {
                                $range_text =
                                    'từ ' . number_format($parts[0]) . 'đ đến ' . number_format($parts[1]) . 'đ';
                            }
                        } elseif (count($parts) == 1 && is_numeric($parts[0])) {
                            $range_text = 'trên ' . number_format($parts[0]) . 'đ';
                        }
                    @endphp
                    @if ($range_text)
                        Đang lọc sản phẩm có giá {{ $range_text }}. <a href="{{ URL::current() }}">Bỏ lọc</a>
                    @endif
                </div>
            @endif
        </div>
        @forelse ($all_product as $key => $product)
            <div class="col-sm-4">
                <div class="product-image-wrapper">
                    <div class="single-products">
                        <div class="productinfo text-center">
                            <form>
                                @csrf
                                <input type="hidden" value="{{ $product->product_id }}"
                                    class="cart_product_id_{{ $product->product_id }}">
                                <input type="hidden" value="{{ $product->product_name }}"
                                    class="cart_product_name_{{ $product->product_id }}">
                                <input type="hidden" value="{{ $product->product_image }}"
                                    class="cart_product_image_{{ $product->product_id }}">
                                <input type="hidden" value="{{ $product->product_price }}"
                                    class="cart_product_price_{{ $product->product_id }}">
                                <input type="hidden" value="1" class="cart_product_qty_{{ $product->product_id }}">
                                <a href="{{ URL::to('/chi-tiet-san-pham/' . $product->product_id) }}">
                                    <img src="{{ URL::to('public/Uploads/product/' . $product->product_image) }}"
                                        alt="" />
                                    <h2>{{ number_format($product->product_price) . ' VNĐ' }}</h2>
                                    <p>{{ $product->product_name }}</p>
                                </a>
                                <button type="button" class="btn btn-default add-to-cart" name="add-to-cart"
                                    data-id="{{ $product->product_id }}">
                                    <i class="fa fa-shopping-cart"></i> Thêm vào giỏ hàng
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-sm-12">
                <p class="text-center text-muted">Không tìm thấy sản phẩm nào phù hợp với bộ lọc.</p>
            </div>
        @endforelse
    </div><!--features_items-->
    <div class="row">
        <div class="col-sm-12 text-center">
            {{-- appends(request()->all()) giúp giữ lại các tham số lọc khi chuyển trang --}}
            {{ $all_product->appends(request()->all())->links() }}
        </div>
    </div>
@endsection
