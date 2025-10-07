@extends('index')
@section('content')
    <section id="cart_items">
        <div class="container">
            <div class="breadcrumbs">
                <ol class="breadcrumb">
                    <li><a href="">Trang chủ</a></li>
                    <li class="active">Giỏ hàng của bạn</li>
                </ol>
            </div>
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @elseif(session()->has('error'))
                <div class="alert alert-danger">
                    {{ session()->get('error') }}
                </div>
            @endif

            <div class="table-responsive cart_info">
                <form action="{{ url('/update-cart') }}" method="POST">
                    @csrf
                    <table class="table table-condensed">
                        <thead>
                            <tr class="cart_menu">
                                <td class="image">Hình ảnh</td>
                                <td class="description">Tên sản phẩm</td>
                                <td class="price">Giá sản phẩm </td>
                                <td class="quantity">Số lượng</td>
                                <td class="total">Thành tiền</td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                            @if (Session::has('cart') && count(Session::get('cart')) > 0)
                                @php
                                    $tongtien = 0;
                                @endphp

                                @foreach (Session::get('cart') as $key => $cart)
                                    @php
                                        $product_id = $cart['product_id'];
                                        $product_stock_info = $products_in_stock[$product_id] ?? null;
                                        $error_message = '';

                                        if (!$product_stock_info) {
                                            $error_message = 'Sản phẩm này hiện không còn kinh doanh.';
                                            $is_checkout_disabled = true;
                                        } elseif ($cart['product_qty'] > $product_stock_info->product_quantity) {
                                            $error_message =
                                                'Số lượng tồn kho không đủ (chỉ còn ' .
                                                $product_stock_info->product_quantity .
                                                '). Vui lòng cập nhật lại.';
                                            $is_checkout_disabled = true;
                                        }

                                        $price = $cart['product_price'] ?? 0;
                                        $qty = $cart['product_qty'] ?? 1;
                                        $thanhtien = $price * $qty;
                                        $tongtien += $thanhtien;
                                    @endphp
                                    <tr>
                                        <td class="cart_product">
                                            {{-- Kiểm tra key 'product_image' trước khi dùng --}}
                                            <img src="{{ asset('public/Uploads/product/' . ($cart['product_image'] ?? 'default.jpg')) }}"
                                                alt="{{ $cart['product_name'] ?? 'Sản phẩm' }}" width="50" />
                                        </td>
                                        <td class="cart_description">
                                            <h4><a href=""></a></h4>
                                            <p>{{ $cart['product_name'] ?? 'Không có tên' }}</p>
                                        </td>
                                        <td class="cart_price">
                                            <p>{{ number_format($price, 0, ',', '.') }} vnđ</p>
                                        </td>
                                        <td class="cart_quantity">
                                            <div class="cart_quantity_button">
                                                <input class="cart_quantity" type="number" min="1"
                                                    name="cart_qty[{{ $cart['session_id'] ?? '' }}]"
                                                    value="{{ $qty }}">
                                            </div>
                                        </td>
                                        <td class="cart_total">
                                            <p class="cart_total_price">{{ number_format($thanhtien, 0, ',', '.') }} vnđ
                                            </p>
                                        </td>
                                        <td class="cart_delete">
                                            <a class="cart_quantity_delete"
                                                href="{{ url('/del-product/' . ($cart['session_id'] ?? '')) }}"><i
                                                    class="fa fa-times"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td><input type="submit" value="Cập nhật giỏ hàng" name="update_qty"
                                            class="check_out btn btn-default btn-sm"></td>
                                    <td><a class="btn btn-default check_out" href="{{ url('/del-all-product') }}">Xóa tất
                                            cả</a></td>
                                    <td>
                                        @if (Session::get('coupon'))
                                            <a class="btn btn-default check_out" href="{{ url('/unset-coupon') }}">Xóa
                                                mã khuyến mãi</a>
                                        @endif
                                    </td>
                                    <td>
                                        @if (Session::get('customer_id'))
                                            <a class="btn btn-default check_out" href="{{ url('/checkout') }}">Đặt hàng</a>
                                        @else
                                            <a class="btn btn-default check_out" href="{{ url('/login-checkout') }}">Đặt
                                                hàng</a>
                                        @endif
                                    </td>
                                    <td colspan="2">
                                        <li>Tổng tiền :<span>{{ number_format($tongtien, 0, ',', '.') }}đ</span></li>
                                        @if (Session::has('coupon'))
                                            <li>
                                                @foreach (Session::get('coupon') as $key => $cou)
                                                    {{-- Điều kiện 1: Giảm giá theo phần trăm --}}
                                                    @if ($cou['coupon_condition'] == 1)
                                                        Mã giảm: {{ $cou['coupon_number'] }} %
                                                        @php
                                                            $total_coupon = ($tongtien * $cou['coupon_number']) / 100;
                                                        @endphp
                                                        <p>Tổng giảm: {{ number_format($total_coupon, 0, ',', '.') }}đ</p>
                                                        <p><b>Thành tiền:
                                                                {{ number_format($tongtien - $total_coupon, 0, ',', '.') }}đ</b>
                                                        </p>

                                                        {{-- Điều kiện 2: Giảm giá theo số tiền cố định --}}
                                                    @elseif($cou['coupon_condition'] == 2)
                                                        Mã giảm: {{ number_format($cou['coupon_number'], 0, ',', '.') }}đ
                                                        @php
                                                            $total_coupon = $tongtien - $cou['coupon_number'];
                                                        @endphp
                                                        <p><b>Thành tiền:
                                                                {{ number_format($total_coupon, 0, ',', '.') }}đ</b></p>
                                                    @endif
                                                @endforeach
                                            </li>
                                        @endif
                                        {{-- <li>Thuế <span></span></li>
                                        <li>Phí vận chuyển<span>Free</span></li> --}}
                                    </td>
                                </tr>
                            @else
                                <tr>>
                                    <td colspan="5">
                                        <center>
                                            @php
                                                echo 'Làm ơn thêm sản phẩm vào giỏ hàng';
                                            @endphp
                                        </center>
                                    </td>
                                </tr>
                                @php
                                    $tongtien = 0;
                                @endphp
                            @endif
                        </tbody>
                </form>
                @if (Session::get('cart'))
                    <tr>
                        <td>
                            <form method="POST" action="{{ url('/check-coupon') }}">
                                @csrf
                                <input type="text" name="coupon" class="form-control"
                                    placeholder="Nhập mã giảm giá"><br>
                                <input type="submit" class="btn btn-default check_coupon" name="check_coupon"
                                    value="Tính mã giảm giá">
                            </form>
                        </td>
                    </tr>
                @endif
                </table>
            </div>
        </div>
    </section>
@endsection
