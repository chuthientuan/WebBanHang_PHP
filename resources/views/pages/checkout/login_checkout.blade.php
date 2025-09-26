@extends('index')
@section('content')
    <section id="form"><!--form-->
        <div class="container">
            <div class="row">
                <div class="col-sm-4 col-sm-offset-1">
                    <div class="login-form">
                        <h2>Đăng nhập</h2>
                        {{-- Hiển thị lỗi đăng nhập --}}
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        <form action="{{ URL::to('/login-customer') }}" method="POST">
                            {{ csrf_field() }}
                            <input type="text" name="email_account" placeholder="Tài khoản" />
                            <input type="password" name="password_account" placeholder="Mật khẩu" />
                            <span>
                                <input type="checkbox" class="checkbox">
                                Giữ đăng nhập
                            </span>
                            <button type="submit" class="btn btn-default">Đăng nhập</button>
                        </form>
                    </div>
                </div>
                <div class="col-sm-1">
                    <h2 class="or">Hoặc</h2>
                </div>
                <div class="col-sm-4">
                    <div class="signup-form">
                        <h2>Đăng ký</h2>
                        <form action="{{ URL::to('/add-customer') }}" method="POST">
                            {{ csrf_field() }}
                            <input type="text" name="customer_name" placeholder="Họ và tên"
                                value="{{ old('customer_name') }}" />
                            <input type="email" name="customer_email" placeholder="Địa chỉ email"
                                value="{{ old('customer_email') }}" />
                            @error('customer_email')
                                <span class="text-danger" style="font-size: 14px;">{{ $message }}</span>
                            @enderror

                            <input type="password" name="customer_password" placeholder="Mật khẩu" />
                            @error('customer_password')
                                <span class="text-danger" style="font-size: 14px;">{{ $message }}</span>
                            @enderror
                            {{-- THÊM Ô NHẬP LẠI MẬT KHẨU --}}
                            <input type="password" name="customer_password_confirmation" placeholder="Nhập lại mật khẩu" />
                            <input type="text" name="customer_phone" placeholder="Số điện thoại"
                                value="{{ old('customer_phone') }}" />
                            <button type="submit" class="btn btn-default">Đăng ký</button>
                        </form>
                    </div><!--/sign up form-->
                </div>
            </div>
        </div>
    </section><!--/form-->
@endsection
