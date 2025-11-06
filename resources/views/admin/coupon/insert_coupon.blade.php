@extends('admin_layout')
@section('admin_content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    Thêm mã giảm giá
                </header>
                <div class="panel-body">

                    {{-- Hiển thị thông báo thành công --}}
                    @if (session('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                        {{ session()->forget('message') }}
                    @endif

                    <div class="position-center">
                        <form role="form" action="{{ URL::to('/insert-coupon-code') }}" method="post">
                            @csrf

                            {{-- 1. Tên mã giảm giá --}}
                            <div class="form-group">
                                <label for="couponName">Tên mã giảm giá</label>
                                <input type="text" name="coupon_name" class="form-control" id="couponName"
                                    value="{{ old('coupon_name') }}">
                                {{-- Hiển thị lỗi ngay bên dưới input --}}
                                @error('coupon_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- 2. Mã giảm giá (Tự động sinh) --}}
                            <div class="form-group">
                                <label for="generatedCode">Mã giảm giá (Tự động sinh)</label>
                                <input type="text" name="display_coupon_code" class="form-control" id="generatedCode"
                                    value="{{ $generated_code }}" readonly
                                    style="background-color: #eee; cursor: not-allowed;">
                                <input type="hidden" name="generated_coupon_code" value="{{ $generated_code }}">
                                {{-- Hiển thị lỗi (ví dụ: mã bị trùng) --}}
                                @error('generated_coupon_code')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- 3. Số lượng mã --}}
                            <div class="form-group">
                                <label for="couponTime">Số lượng mã</label>
                                <input type="number" min="1" class="form-control" name="coupon_time" id="couponTime"
                                    value="{{ old('coupon_time') }}">
                                {{-- Hiển thị lỗi --}}
                                @error('coupon_time')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- 4. Tính năng mã --}}
                            <div class="form-group">
                                <label for="couponCondition">Tính năng mã</label>
                                <select name="coupon_condition" class="form-control input-sm m-bot15" id="couponCondition"
                                >
                                    <option value="">-----chọn----</option>
                                    <option value="1" {{ old('coupon_condition') == '1' ? 'selected' : '' }}>
                                        Giảm theo phần trăm (%)
                                    </option>
                                    <option value="2" {{ old('coupon_condition') == '2' ? 'selected' : '' }}>
                                        Giảm theo số tiền (đ)
                                    </option>
                                </select>
                            </div>

                            {{-- 5. Số % hoặc số tiền giảm --}}
                            <div class="form-group">
                                <label for="couponNumber">Nhập số % hoặc số tiền giảm</label>
                                <input type="number" min="1" step="any" class="form-control"
                                    name="coupon_number" id="couponNumber" value="{{ old('coupon_number') }}">
                                {{-- Hiển thị lỗi --}}
                                @error('coupon_number')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <button type="submit" name="add_coupon" class="btn btn-info">Thêm mã</button>
                        </form>
                    </div>

                </div>
            </section>
        </div>
    </div>

    {{-- Thêm CSS để định dạng màu cho text-danger nếu theme của bạn chưa có --}}
    <style>
        .text-danger {
            color: #d9534f;
        }
    </style>
@endsection
