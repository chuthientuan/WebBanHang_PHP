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
             @if(session() -> has('message'))
                <div class="alert alert-success">
                    {{session()->get('message')}}
                </div>
            @elseif(session() -> has('error'))
                <div class="alert alert-danger">
                    {{session()->get('error')}}
                </div>
            @endif

            <div class="table-responsive cart_info">
                <form action="{{url('/update-cart')}}" method="POST">
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
                        @if(Session::get('cart')==true)
                        @php
                            $tongtien =0;
                        @endphp

                       @foreach(Session::get('cart') as $key => $cart)
                            @php
                            $thanhtien = $cart['product_price'] * $cart['product_qty'];
                            $tongtien += $thanhtien;
                            @endphp
                            <tr>
                                <td class="cart_product">  
                                    <img src="{{asset('public/Uploads/product/'.$cart['product_image'])}}" alt="{{$cart['product_name']}}"  width="50"/>
                                </td>
                                <td class="cart_description">
                                    <h4><a href=""></a></h4>
                                    <p>{{$cart['product_name']}}</p>
                                </td>
                                <td class="cart_price">
                                    <p>{{number_format($cart['product_price'],0,',','.')}} vnđ</p>
                                </td>
                                <td class="cart_quantity">
                                    <div class="cart_quantity_button">
                                       
                                         
                                            <input class="cart_quantity" type="number" min="1" name="cart_qty[{{$cart['session_id']}}]" value="{{$cart['product_qty']}}">

                                       
                                    </div>
                                </td>
                                <td class="cart_total">
                                    <p class="cart_total_price">{{number_format($thanhtien,0,',','.')}} vnđ</p>
                                </td>
                                <td class="cart_delete">
                                    <a class="cart_quantity_delete"
                                        href="{{url('/del-product/'.$cart['session_id'])}}"><i
                                            class="fa fa-times"></i></a>
                                </td>
                            </tr>
                            
                        @endforeach
                        <tr>
                            <td><input type="submit" name="update_qty" value="Cập nhật giỏ hàng"class=" check_out btn btn-default btn-sm"></td>
                             <td><a class="btn btn-default check_out" href="{{url('/del-all-product/')}}">xóa tất cả giỏ hàng </a></td>
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
                            $tongtien =0;
                        @endphp 
                        @endif
                    </tbody>
                        
                        </form>
                </table>
            </div>
        </div>
    </section> <!--/#cart_items-->
    <section id="do_action">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="total_area">
                        <ul>
                            <li>Tổng Tiền : <span>{{number_format($tongtien,0,',','.')}} vnđ</span></li>
                            <li>Thuế<span></span></li>
                            <li>Phí vận chuyển<span>Free</span></li>
                            <li>Tiền sau giảm <span></span></li>
                        </ul>
                            <!-- <a class="btn btn-default check_out" href="">Thanh Toán</a> -->
                            <form action="{{url('/check-coupon')}}" method="POST">
                            @csrf
                            <input type="text" class="form-control" name="coupon" placeholder="Nhập mã giảm giá"><br>
                            <input type="submit" class="btn btn-default check_coupon" value="Tính Mã Giảm Giá" name="check_coupon"> 
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </section><!--/#do_action-->
@endsection
