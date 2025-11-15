<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | E-Shopper</title>

    {{-- CSS --}}
    <link href="{{ asset('frontend/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/css/prettyPhoto.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/css/price-range.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/css/responsive.css') }}" rel="stylesheet">

    <link href="{{ asset('frontend/css/sweetalert.css') }}" rel="stylesheet">

    {{-- JS --}}

    <!--[if lt IE 9]>
    <script src="{{ asset('frontend/js/html5shiv.js') }}"></script>
    <script src="{{ asset('frontend/js/respond.min.js') }}"></script>
    <![endif]-->

    <link rel="shortcut icon" href="{{ asset('frontend/images/ico/favicon.ico') }}">
    <link rel="apple-touch-icon-precomposed" sizes="144x144"
        href="{{ asset('frontend/images/ico/apple-touch-icon-144-precomposed.png') }}">
    <link rel="apple-touch-icon-precomposed" sizes="114x114"
        href="{{ asset('frontend/images/ico/apple-touch-icon-114-precomposed.png') }}">
    <link rel="apple-touch-icon-precomposed" sizes="72x72"
        href="{{ asset('frontend/images/ico/apple-touch-icon-72-precomposed.png') }}">
    <link rel="apple-touch-icon-precomposed"
        href="{{ asset('frontend/images/ico/apple-touch-icon-57-precomposed.png') }}">
</head><!--/head-->

<body>
    <header id="header"><!--header-->
        <div class="header_top"><!--header_top-->
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="contactinfo">
                            <ul class="nav nav-pills">
                                <li><a href="#"><i class="fa fa-phone"></i> +2 95 01 88 821</a></li>
                                <li><a href="#"><i class="fa fa-envelope"></i> info@domain.com</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="social-icons pull-right">
                            <ul class="nav navbar-nav">
                                <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                                <li><a href="#"><i class="fa fa-dribbble"></i></a></li>
                                <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--/header_top-->

        <div class="header-middle"><!--header-middle-->
            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="logo pull-left">
                            <a href="index.html"><img src="{{ asset('frontend/images/home/logo.png') }}"
                                    alt="" /></a>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="shop-menu pull-right">
                            <ul class="nav navbar-nav">
                                <?php
                                    $customer_id = Session::get('customer_id');
                                    if ($customer_id != NULL) {
                                ?>
                                <li><a href="{{ URL::to('/checkout') }}"><i class="fa fa-crosshairs"></i>Thanh toán</a>
                                </li>
                                <?php
                                    } else {
                                ?>
                                <li><a href="{{ URL::to('/login-checkout') }}"><i class="fa fa-crosshairs"></i>Thanh
                                        toán</a></li>
                                <?php
                                    }   
                                ?>


                                <li><a href="{{ URL::to('/gio-hang') }}"><i class="fa fa-shopping-cart"></i>Giỏ
                                        hàng</a></li>

                                @if (Session::has('customer_id'))
                                    <li>
                                        <a href="{{ URL::to('/history') }}">
                                            <i class="fa fa-bell"></i> Lịch sử đơn hàng
                                        </a>
                                    </li>
                                @endif

                                <?php
                                    $customer_id = Session::get('customer_id');
                                    if ($customer_id != NULL) {
                                ?>
                                <li><a href="{{ URL::to('/logout-checkout') }}"><i class="fa fa-lock"></i>Đăng xuất</a>
                                </li>
                                <?php
                                    } else {
                                ?>
                                <li><a href="{{ URL::to('/login-home') }}"><i class="fa fa-lock"></i>Đăng nhập</a>
                                </li>
                                <?php
                                    }   
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--/header-middle-->

        <div class="header-bottom"><!--header-bottom-->
            <div class="container">
                <div class="row">
                    <div class="col-sm-8">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle" data-toggle="collapse"
                                data-target=".navbar-collapse">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                        </div>
                        <div class="mainmenu pull-left">
                            <ul class="nav navbar-nav collapse navbar-collapse">
                                <li><a href="{{ URL::to('/trang-chu') }}" class="active">Trang chủ</a></li>
                                <li class="dropdown"><a href="#">Sản phẩm<i class="fa fa-angle-down"></i></a>
                                    <ul role="menu" class="sub-menu">
                                        @foreach ($category as $key => $cate)
                                            <li><a
                                                    href="{{ URL::to('/danh-muc-san-pham/' . $cate->category_id) }}">{{ $cate->category_name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>

                                </li>
                                <li><a href="{{ URL::to('/gio-hang') }}">Giỏ hàng</a></li>
                                <li><a href="contact-us.html">Liên hệ</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <form action="{{ URL::to('/tim-kiem') }}" method="POST">
                            {{ csrf_field() }}

                            <div class="search_box pull-right">
                                <input type="text" name="keywords_submit" placeholder="Tìm Kiếm sản phẩm " />
                                <input type="submit" name="search_items" class="btn btn-info btn-sm"
                                    value="Tìm kiếm">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div><!--/header-bottom-->
    </header><!--/header-->

    <section id="slider"><!--slider-->
        @yield('slider')
    </section><!--/slider-->

    <section>
        <div class="container">
            <div class="row">
                @yield('sidebar')
                <div class="col-sm-9 padding-right">
                    @yield('content')
                </div>
            </div>
        </div>
    </section>

    <footer id="footer"><!--Footer-->
        <div class="footer-top">
            <div class="container">
                <div class="row">
                    <div class="col-sm-2">
                        <div class="companyinfo">
                            <h2><span>e</span>-shopper</h2>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit,sed do eiusmod tempor</p>
                        </div>
                    </div>
                    <div class="col-sm-7">
                        <div class="col-sm-3">
                            <div class="video-gallery text-center">
                                <a href="#">
                                    <div class="iframe-img">
                                        <img src="{{ asset('frontend/images/home/foot1.jpg') }}" alt="" />
                                    </div>
                                    <div class="overlay-icon">
                                        <i class="fa fa-play-circle-o"></i>
                                    </div>
                                </a>
                                <p>Circle of Hands</p>
                                <h2>24 DEC 2014</h2>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="video-gallery text-center">
                                <a href="#">
                                    <div class="iframe-img">
                                        <img src="{{ asset('frontend/images/home/foot2.jpg') }}" alt="" />
                                    </div>
                                    <div class="overlay-icon">
                                        <i class="fa fa-play-circle-o"></i>
                                    </div>
                                </a>
                                <p>Circle of Hands</p>
                                <h2>24 DEC 2014</h2>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="video-gallery text-center">
                                <a href="#">
                                    <div class="iframe-img">
                                        <img src="{{ asset('frontend/images/home/foot3.jpg') }}" alt="" />
                                    </div>
                                    <div class="overlay-icon">
                                        <i class="fa fa-play-circle-o"></i>
                                    </div>
                                </a>
                                <p>Circle of Hands</p>
                                <h2>24 DEC 2014</h2>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="video-gallery text-center">
                                <a href="#">
                                    <div class="iframe-img">
                                        <img src="{{ asset('frontend/images/home/foot4.jpg') }}" alt="" />
                                    </div>
                                    <div class="overlay-icon">
                                        <i class="fa fa-play-circle-o"></i>
                                    </div>
                                </a>
                                <p>Circle of Hands</p>
                                <h2>24 DEC 2014</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="address">
                            <img src="{{ asset('frontend/images/home/map.png') }}" alt="" />
                            <p>505 S Atlantic Ave Virginia Beach, VA(Virginia)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-widget">
            <div class="container">
                <div class="row">
                    <div class="col-sm-2">
                        <div class="single-widget">
                            <h2>Service</h2>
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="#">Online Help</a></li>
                                <li><a href="#">Contact Us</a></li>
                                <li><a href="#">Order Status</a></li>
                                <li><a href="#">Change Location</a></li>
                                <li><a href="#">FAQ’s</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="single-widget">
                            <h2>Quock Shop</h2>
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="#">T-Shirt</a></li>
                                <li><a href="#">Mens</a></li>
                                <li><a href="#">Womens</a></li>
                                <li><a href="#">Gift Cards</a></li>
                                <li><a href="#">Shoes</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="single-widget">
                            <h2>Policies</h2>
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="#">Terms of Use</a></li>
                                <li><a href="#">Privecy Policy</a></li>
                                <li><a href="#">Refund Policy</a></li>
                                <li><a href="#">Billing System</a></li>
                                <li><a href="#">Ticket System</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="single-widget">
                            <h2>About Shopper</h2>
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="#">Company Information</a></li>
                                <li><a href="#">Careers</a></li>
                                <li><a href="#">Store Location</a></li>
                                <li><a href="#">Affillate Program</a></li>
                                <li><a href="#">Copyright</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-3 col-sm-offset-1">
                        <div class="single-widget">
                            <h2>About Shopper</h2>
                            <form action="#" class="searchform">
                                <input type="text" placeholder="Your email address" />
                                <button type="submit" class="btn btn-default"><i
                                        class="fa fa-arrow-circle-o-right"></i></button>
                                <p>Get the most recent updates from <br />our site and be updated your self...</p>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="container">
                <div class="row">
                    <p class="pull-left">Copyright © 2013 E-SHOPPER Inc. All rights reserved.</p>
                    <p class="pull-right">Designed by <span><a target="_blank"
                                href="http://www.themeum.com">Themeum</a></span></p>
                </div>
            </div>
        </div>

    </footer><!--/Footer-->


    <script src="{{ asset('frontend/js/jquery.js') }}"></script>
    <script src="{{ asset('frontend/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('frontend/js/jquery.scrollUp.min.js') }}"></script>
    <script src="{{ asset('frontend/js/price-range.js') }}"></script>
    <script src="{{ asset('frontend/js/jquery.prettyPhoto.js') }}"></script>
    <script src="{{ asset('frontend/js/main.js') }}"></script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.send_order').click(function() {

                // Lấy tất cả dữ liệu từ form trước khi mở popup
                var shipping_name = $('.shipping_name').val();
                var shipping_address = $('.shipping_address').val();
                var shipping_phone = $('.shipping_phone').val();
                var shipping_email = $('.shipping_email').val();
                var shipping_note = $('.shipping_note').val();
                var payment_method = $('.payment_select').val();
                var order_fee = $('.order_fee').val();
                var order_coupon = $('.order_coupon').val();
                var _token = $('input[name="_token"]').val();

                $('.error-text').text('');

                if (!shipping_name || !shipping_address || !shipping_phone || !shipping_email) {
                    Swal.fire('Lỗi!', 'Vui lòng điền đầy đủ thông tin giao hàng.', 'error');
                    // Hiển thị lỗi ngay lập tức
                    if (!shipping_name) $('#error_shipping_name').text('Họ và tên không được để trống.');
                    if (!shipping_address) $('#error_shipping_address').text(
                        'Địa chỉ không được để trống.');
                    if (!shipping_phone) $('#error_shipping_phone').text(
                        'Số điện thoại không được để trống.');
                    if (!shipping_email) $('#error_shipping_email').text('Email không được để trống.');
                    return;
                }

                // Sử dụng cú pháp Swal.fire().then() của SweetAlert 2
                Swal.fire({
                    toast: false,
                    title: 'Xác nhận đơn hàng',
                    text: "Đơn hàng sẽ không được hoàn trả khi đặt, bạn có muốn đặt không?",
                    icon: 'warning', // 'type' đã được đổi thành 'icon'
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Cảm ơn, Mua hàng',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    // Kiểm tra xem người dùng có nhấn nút "confirm" không
                    if (result.isConfirmed) {
                        if (payment_method == '1') {
                            $.ajax({
                                url: "{{ url('/confirm-order') }}",
                                method: 'POST',
                                data: {
                                    shipping_name: shipping_name,
                                    shipping_address: shipping_address,
                                    shipping_phone: shipping_phone,
                                    shipping_email: shipping_email,
                                    shipping_note: shipping_note,
                                    payment_method: payment_method,
                                    order_fee: order_fee,
                                    order_coupon: order_coupon,
                                    _token: _token
                                },
                                success: function(response) {
                                    if (response.status == 'success_saved') {
                                        Swal.fire(
                                            'Đã đặt hàng!',
                                            'Đơn hàng của bạn đã được gửi thành công.',
                                            'success'
                                        ).then(() => {
                                            location.reload();
                                        });
                                    } else {
                                        Swal.fire('Lỗi!',
                                            'Có lỗi xảy ra khi lưu đơn hàng.',
                                            'error');
                                    }
                                },
                                error: function(xhr) {
                                    // 1. Kiểm tra nếu là lỗi validation (422)
                                    if (xhr.status === 422) {
                                        var errors = xhr.responseJSON.errors;
                                        // Hiển thị thông báo lỗi chung
                                        Swal.fire('Lỗi!',
                                            'Dữ liệu không hợp lệ, vui lòng kiểm tra lại.',
                                            'error');

                                        // 2. Hiển thị từng lỗi bên dưới mỗi input
                                        if (errors.shipping_name) {
                                            $('#error_shipping_name').text(errors
                                                .shipping_name[0]);
                                        }
                                        if (errors.shipping_address) {
                                            $('#error_shipping_address').text(errors
                                                .shipping_address[0]);
                                        }
                                        if (errors.shipping_phone) {
                                            $('#error_shipping_phone').text(errors
                                                .shipping_phone[0]);
                                        }
                                        if (errors.shipping_email) {
                                            $('#error_shipping_email').text(errors
                                                .shipping_email[0]);
                                        }
                                    } else {
                                        // 2. Xử lý các lỗi khác (500, 404, ...)
                                        var errorMsg =
                                            'Có lỗi xảy ra, vui lòng thử lại.';
                                        if (xhr.responseJSON && xhr.responseJSON
                                            .message) {
                                            errorMsg = xhr.responseJSON.message;
                                        }
                                        Swal.fire('Lỗi!', errorMsg, "error");
                                    }
                                }
                            });
                        } else {
                            $.ajax({
                                url: "{{ url('/generate-qr-code') }}", // Route MỚI: chỉ tạo QR
                                method: 'POST',
                                data: {
                                    order_fee: order_fee,
                                    order_coupon: order_coupon,
                                    _token: _token
                                },
                                success: function(response) {
                                    if (response.status == 'qr_generated') {

                                        // BƯỚC 2.2: Hiển thị popup QR
                                        Swal.fire({
                                            title: 'Vui lòng quét mã QR',
                                            html: `
                                            <p>Quét mã QR để hoàn tất đơn hàng.</p>
                                            <img src="${response.qr_data}" alt="Mã QR" style="width: 250px; height: 250px; margin: 15px auto; display: block;">
                                            <p>Mã đơn hàng: <strong>${response.order_code}</strong></p>
                                            <p style="color: #d33;">Số tiền: <strong>${Number(response.amount).toLocaleString('vi-VN')} đ</strong></p>
                                        `,
                                            icon: 'info',
                                            confirmButtonText: 'Đã thanh toán / Hoàn tất',
                                            showCancelButton: true,
                                            cancelButtonText: 'Hủy đơn'
                                        }).then((qrResult) => {
                                            if (qrResult.isConfirmed) {

                                                $.ajax({
                                                    url: "{{ url('/confirm-order') }}", // Dùng lại route LƯU
                                                    method: 'POST',
                                                    data: {
                                                        shipping_name: shipping_name,
                                                        shipping_address: shipping_address,
                                                        shipping_phone: shipping_phone,
                                                        shipping_email: shipping_email,
                                                        shipping_note: shipping_note,
                                                        payment_method: payment_method,
                                                        order_fee: order_fee,
                                                        order_coupon: order_coupon,
                                                        _token: _token,
                                                        order_code: response
                                                            .order_code
                                                    },
                                                    success: function(
                                                        saveResponse
                                                    ) {
                                                        if (saveResponse
                                                            .status ==
                                                            'success_saved'
                                                        ) {
                                                            Swal.fire(
                                                                    'Thành công!',
                                                                    'Đã xác nhận thanh toán và lưu đơn hàng.',
                                                                    'success'
                                                                )
                                                                .then(
                                                                    () => {
                                                                        location
                                                                            .reload();
                                                                    }
                                                                );
                                                        } else {
                                                            Swal.fire(
                                                                'Lỗi!',
                                                                'Có lỗi xảy ra khi lưu đơn hàng.',
                                                                'error'
                                                            );
                                                        }
                                                    },
                                                    error: function() {
                                                        Swal.fire(
                                                            'Lỗi!',
                                                            'Không thể kết nối máy chủ để lưu.',
                                                            'error'
                                                        );
                                                    }
                                                }); // Kết thúc AJAX (2)

                                            } else {
                                                // Người dùng bấm "Hủy" trên popup QR
                                                Swal.fire('Đã hủy',
                                                    'Đơn hàng chưa được lưu.',
                                                    'error');
                                                // Không làm gì cả, giỏ hàng vẫn còn nguyên
                                            }
                                        }); // Kết thúc .then() của popup QR

                                    } else {
                                        Swal.fire('Lỗi!', 'Không thể tạo mã QR.',
                                            'error');
                                    }
                                },
                                error: function() {
                                    Swal.fire('Lỗi!',
                                        'Không thể kết nối máy chủ để tạo QR.',
                                        'error');
                                }
                            });
                        }
                    } else {
                        Swal.fire(
                            'Đã hủy',
                            'Đơn hàng chưa được gửi.',
                            'error'
                        );
                    }
                });
            });
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.add-to-cart').click(function() {
                var id = $(this).data('id');
                var cart_product_id = $('.cart_product_id_' + id).val();
                var cart_product_name = $('.cart_product_name_' + id).val();
                var cart_product_image = $('.cart_product_image_' + id).val();
                var cart_product_price = $('.cart_product_price_' + id).val();
                var cart_product_qty = $('.cart_product_qty_' + id).val();
                var _token = $('input[name="_token"]').val();

                $.ajax({
                    url: "{{ url('/add-cart-ajax') }}",
                    method: 'POST',
                    data: {
                        cart_product_id: cart_product_id,
                        cart_product_name: cart_product_name,
                        cart_product_image: cart_product_image,
                        cart_product_price: cart_product_price,
                        cart_product_qty: cart_product_qty,
                        _token: _token
                    },
                    success: function(data) {
                        // SỬA LẠI TỪ ĐÂY: Dùng Swal.fire() và cấu trúc options mới
                        Swal.fire({
                            toast: false,
                            title: 'Đã thêm sản phẩm',
                            text: 'Sản phẩm đã được thêm vào giỏ hàng của bạn.',
                            icon: 'success',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Đi đến giỏ hàng',
                            cancelButtonText: 'Xem tiếp'
                        }).then((result) => {
                            // 'result.isConfirmed' sẽ là true nếu người dùng bấm nút 'confirm'
                            if (result.isConfirmed) {
                                window.location.href = "{{ url('/gio-hang') }}";
                            }
                        });
                    },
                    error: function() {
                        // Thêm phần xử lý lỗi để người dùng biết
                        Swal.fire({
                            title: 'Lỗi!',
                            text: 'Có lỗi xảy ra, vui lòng thử lại.',
                            icon: 'error'
                        });
                    }
                });
            });
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.add-to-cart-detail').click(function(e) {
                e.preventDefault(); // Ngăn hành vi mặc định

                // Tìm form cha gần nhất để lấy dữ liệu
                var form = $(this).closest('form');

                var qty = form.find('input[name="qty"]').val();
                var product_id = form.find('input[name="productid_hidden"]').val();
                var _token = form.find('input[name="_token"]').val();

                // Gửi AJAX request đến hàm save_cart
                $.ajax({
                    url: '{{ url('/save-cart') }}', // Gọi đến route của hàm save_cart
                    method: 'POST',
                    data: {
                        qty: qty,
                        productid_hidden: product_id,
                        _token: _token
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                title: 'Thành công!',
                                text: response.message,
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                // Chuyển hướng đến trang giỏ hàng sau khi người dùng bấm OK
                                if (result.isConfirmed) {
                                    window.location.href = "{{ url('/gio-hang') }}";
                                }
                            });
                        }
                    },
                    error: function(xhr) {
                        // Xử lý lỗi (ví dụ: không đủ hàng)
                        var errorMsg = 'Có lỗi xảy ra, vui lòng thử lại.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            title: 'Lỗi!',
                            text: errorMsg,
                            icon: 'error'
                        });
                    }
                });
            });
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.choose').on('change', function() {
                var action = $(this).attr('id');
                var ma_id = $(this).val();
                var _token = $('input[name="_token"]').val();
                var result = '';
                if (action == 'city') {
                    result = 'province';
                } else {
                    result = 'wards';
                }
                $.ajax({
                    url: "{{ url('/select-delivery-home') }}",
                    method: 'POST',
                    data: {
                        action: action,
                        ma_id: ma_id,
                        _token: _token
                    },
                    success: function(data) {
                        $('#' + result).html(data);
                    }
                });
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.calculate_delivery').click(function() {
                var matp = $('.city').val();
                var maqh = $('.province').val();
                var xaid = $('.wards').val();
                var _token = $('input[name="_token"]').val();
                if (matp == '' && maqh == '' && xaid == '') {
                    alert('Làm ơn chọn để tính phí vận chuyển');
                } else {
                    $.ajax({
                        url: "{{ url('/calculate-fee') }}",
                        method: 'POST',
                        data: {
                            matp: matp,
                            maqh: maqh,
                            xaid: xaid,
                            _token: _token
                        },
                        success: function() {
                            location.reload();
                        }
                    });
                }

            });
        });
    </script>
    @yield('scripts1')
    @yield('scripts2')
    @yield('scripts3')
</body>

</html>
