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

                    <div class="col-sm-10 clearfix">
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
                                    <input type="submit" value="Gửi" name="send_order" class="btn btn-primary btn-sm">
                                </form>
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
