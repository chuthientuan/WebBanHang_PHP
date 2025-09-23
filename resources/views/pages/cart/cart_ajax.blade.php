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
            <div class="table-responsive cart_info">
                <?php
                $content = Cart::content();
                ?>
                <table class="table table-condensed">
                    <thead>
                        <tr class="cart_menu">
                            <td class="image">Hình ảnh</td>
                            <td class="description">Mô tả</td>
                            <td class="price">Giá</td>
                            <td class="quantity">Số lượng</td>
                            <td class="total">Tổng tiền</td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            print_r(Session::get('cart'));
                        @endphp
                            <tr>
                                <td class="cart_product">
                                    <a href=""><img
                                            src=""
                                            width="50" alt=""></a>
                                </td>
                                <td class="cart_description">
                                    <h4><a href=""></a></h4>
                                    <p>Web ID: 1089772</p>
                                </td>
                                <td class="cart_price">
                                    <p></p>
                                </td>
                                <td class="cart_quantity">
                                    <div class="cart_quantity_button">
                                        <form action="" method="POST">
                                         
                                            <input class="cart_quantity_input" type="text" name="cart_quantity"
                                                value="">
                                            <input type="hidden" value="" name="rowId_cart"
                                                class="form-control">
                                            <input type="submit" name="update_qty" value="Cập nhật"
                                                class="btn btn-default btn-sm">
                                        </form>
                                    </div>
                                </td>
                                <td class="cart_total">
                                    <p class="cart_total_price">
                                </td>
                                <td class="cart_delete">
                                    <a class="cart_quantity_delete"
                                        href=""><i
                                            class="fa fa-times"></i></a>
                                </td>
                            </tr>
                        
                    </tbody>
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
                            <li>Tổng<span></span></li>
                            <li>Thuế<span></span></li>
                            <li>Phí vận chuyển<span>Free</span></li>
                            <li>Thành tiền<span></span></li>
                        </ul>
                                   <a class="btn btn-default check_out" href="">Check Out</a>
                               
                                    <a class="btn btn-default check_out" href="">Check Out</a>
                        
                    </div>
                </div>
            </div>
        </div>
    </section><!--/#do_action-->
@endsection
