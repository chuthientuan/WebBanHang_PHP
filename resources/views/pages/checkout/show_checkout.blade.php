@extends('index')
@section('content')
    <section id="cart_items">
        <div class="container">
            <div class="breadcrumbs">
                <ol class="breadcrumb">
                    <li><a href="{{ URL::to('/') }}">Trang chủ</a></li>
                    <li class="active">Thanh Toán Giỏ hàng </li>
                </ol>
            </div>

            <div class="shopper-informations">
                <div class="row">
    
                    <div class="col-sm-12 clearfix">
                        <div class="bill-to">
                            <p>Điền thông tin gửi hàng </p>
                            <div class="form-one">
                                <form action="{{ URL::to('/save-checkout-customer') }}" method="POST">
                                    {{ csrf_field() }}
                                    <input type="text" name="shipping_name" placeholder="Họ và tên">
                                    <input type="text" name="shipping_address" placeholder="Địa chi">
                                    <input type="text" name="shipping_phone" placeholder="Phone">
                                    <input type="text" name="shipping_email" placeholder="Email">
                                    <textarea name="shipping_note" placeholder="Ghi chú đơn hàng " rows="16"></textarea>
                                    <input type="submit" value="Xác nhận đơn hàng " name="send_order" class="btn btn-primary btn-sm">
                                </form>
                                <form>
                                    @csrf()
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Chọn thành phố </label>
                                        <select name="city" id="city" class="form-control input-sm m-bot15 choose city">
                                            <option value="">----Chọn tỉnh thành phố----</option>
                                            @foreach ($city as $key => $ci)
                                                <option value="{{ $ci->matp }}">{{ $ci->name_city }}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Chọn quận huyện </label>
                                        <select name="province" id="province"
                                            class="form-control input-sm m-bot15  province choose">
                                            <option value="">----chọn quận huyện----</option>

                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Chọn xã phường </label>
                                        <select name="wards" id="wards" class="form-control input-sm m-bot15 wards">
                                            <option value="">-- --chọn xã phường----</option>

                                        </select>
                                    </div>

                                    <input type="button" value="Tính phí vận chuyển  " name="calculate_order" class="btn btn-primary btn-sm
                                    calculate_delivery">    

                                </form>
                                <?php
                                    echo Session::get('fee');
                                ?>  
                            </div>

                            <div>
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
                                                @if (Session::get('cart') == true)
                                                    @php
                                                        $tongtien = 0;
                                                    @endphp

                                                    @foreach (Session::get('cart') as $key => $cart)
                                                        @php
                                                            $thanhtien = $cart['product_price'] * $cart['product_qty'];
                                                            $tongtien += $thanhtien;
                                                        @endphp
                                                        <tr>
                                                            <td class="cart_product">
                                                                <img src="{{ asset('public/Uploads/product/' . $cart['product_image']) }}"
                                                                    alt="{{ $cart['product_name'] }}" width="50" />
                                                            </td>
                                                            <td class="cart_description">
                                                                <h4><a href=""></a></h4>
                                                                <p>{{ $cart['product_name'] }}</p>
                                                            </td>
                                                            <td class="cart_price">
                                                                <p>{{ number_format($cart['product_price'], 0, ',', '.') }} vnđ</p>
                                                            </td>
                                                            <td class="cart_quantity">
                                                                <div class="cart_quantity_button">
                                                                    <input class="cart_quantity" type="number" min="1"
                                                                        name="cart_qty[{{ $cart['session_id'] }}]"
                                                                        value="{{ $cart['product_qty'] }}">
                                                                </div>
                                                            </td>
                                                            <td class="cart_total">
                                                                <p class="cart_total_price">{{ number_format($thanhtien, 0, ',', '.') }} vnđ</p>
                                                            </td>
                                                            <td class="cart_delete">
                                                                <a class="cart_quantity_delete"
                                                                    href="{{ url('/del-product/' . $cart['session_id']) }}"><i
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

                        </div>
                    </div>  
                </div>
            </div>
            <div class="review-payment">
                <h2>Xem lại giỏ hàng </h2>
            </div>


            <div class="payment-options">
                <span>
                    <label><input type="checkbox">Trả bằng thẻ ATM</label>
                </span>
                <span>
                    <label><input type="checkbox">Trả tiền mặt</label>
                </span>
                <span>
                    <label><input type="checkbox">Thanh toán thẻ ghi nợ</label>
                </span>
            </div>
        </div>
    </section> <!--/#cart_items-->
@endsection
