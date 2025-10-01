@extends('admin_layout')
@section('admin_content')
    <div class="table-agile-info">
        <div class="panel panel-default">
            <div class="panel-heading">
                Thông tin khách hàng
            </div>
            <div class="table-responsive">
                <table class="table table-striped b-t b-light">
                    <thead>
                        <tr>
                            <th>Tên khách hàng</th>
                            <th>Số điện thoại</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $customer->customer_name }}</td>
                            <td>{{ $customer->customer_phone }}</td>
                            <td>{{ $customer->customer_email }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <br>
    <div class="table-agile-info">
        <div class="panel panel-default">
            <div class="panel-heading">
                Thông tin vận chuyển
            </div>
            <div class="table-responsive">
                <table class="table table-striped b-t b-light">
                    <thead>
                        <tr>
                            <th>Tên người nhận</th>
                            <th>Địa chỉ</th>
                            <th>Số điện thoại</th>
                            <th>Email</th>
                            <th>Ghi chú</th>
                            <th>Hình thức thanh toán</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $shipping->shipping_name }}</td>
                            <td>{{ $shipping->shipping_address }}</td>
                            <td>{{ $shipping->shipping_phone }}</td>
                            <td>{{ $shipping->shipping_email }}</td>
                            <td>{{ $shipping->shipping_note }}</td>
                            <td>
                                @if ($payment->payment_method == 0)
                                    Chuyển khoản
                                @else
                                    Tiền mặt
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <br><br>
    <div class="table-agile-info">
        <div class="panel panel-default">
            <div class="panel-heading">
                Liệt kê chi tiết đơn hàng
            </div>
            <div class="table-responsive">
                <table class="table table-striped b-t b-light">
                    <thead>
                        <tr>
                            <th>Thứ tự</th>
                            <th>Tên sản phẩm</th>
                            <th>Mã giảm giá</th>
                            <th>Phí ship</th>
                            <th>Số lượng</th>
                            <th>Giá sản phẩm</th>
                            <th>Tổng tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 0;
                            $total = 0;
                        @endphp
                        @foreach ($product as $pro)
                            @php
                                $i++;
                                $subtotal = $pro->product_price * $pro->product_sales_quantity;
                                $total += $subtotal;
                            @endphp
                            <tr>
                                <td><i>{{ $i }}</i></td>
                                <td>{{ $pro->product_name }}</td>
                                <td>
                                    @if ($pro->product_coupon != 'no')
                                        {{ $pro->product_coupon }}
                                    @else
                                        Không mã
                                    @endif
                                </td>
                                <td>{{ number_format($pro->product_feeship, 0, ',', '.') }} đ</td>
                                <td>{{ $pro->product_sales_quantity }}</td>
                                <td>{{ number_format($pro->product_price, 0, ',', '.') }} đ</td>
                                <td>{{ number_format($subtotal, 0, ',', '.') }}
                                    đ</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="2">
                                @php
                                    $total_coupon = 0;
                                @endphp
                                @if ($coupon_condition == 1)
                                    @php
                                        // Sửa lỗi cú pháp: Thêm dấu nhân (*)
                                        $total_after_coupon = ($total * $coupon_number) / 100;
                                        echo 'Tổng giảm : ' . number_format($total_after_coupon, 0, ',', '.') . '</br>';
                                        // Giữ nguyên logic trừ phí ship
                                        $total_coupon = $total - $total_after_coupon - $pro->product_feeship;
                                    @endphp
                                @else
                                    @php
                                        echo 'Tổng giảm : ' .
                                            number_format($coupon_number, 0, ',', '.') .
                                            'k' .
                                            '</br>';
                                        // Sửa lỗi cú pháp: Thêm dấu trừ (-)
                                        $total_coupon = $total - $coupon_number - $pro->product_feeship;
                                    @endphp
                                @endif

                                Phí ship : {{ number_format($pro->product_feeship, 0, ',', '.') }}đ</br>
                                Thanh toán: {{ number_format($total_coupon, 0, ',', '.') }}d
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
