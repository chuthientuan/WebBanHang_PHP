@extends('admin_layout')
@section('admin_content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    Cập Nhật Thương Hiệu Sản Phẩm
                </header>
                <div class="panel-body">

                    {{-- Hiển thị thông báo thành công (nếu có) --}}
                    @if (session('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                        {{ session()->forget('message') }}
                    @endif

                    {{-- Bỏ khối hiển thị lỗi chung @if ($errors->any()) --}}

                    <div class="position-center">
                        <form role="form" action="{{ URL::to('/update-brand-product/' . $edit_brand_product->brand_id) }}"
                            method="post">
                            {{ csrf_field() }}

                            {{-- 1. Tên Thương Hiệu --}}
                            <div class="form-group">
                                <label for="brand_product_name">Tên Thương Hiệu</label>
                                <input type="text" {{-- Dùng old() để ưu tiên hiển thị giá trị cũ nếu validation fail --}}
                                    value="{{ old('brand_product_name', $edit_brand_product->brand_name) }}"
                                    name="brand_product_name" class="form-control" id="brand_product_name"
                                    placeholder="Tên Thương Hiệu">

                                {{-- Hiển thị lỗi ngay bên dưới --}}
                                @error('brand_product_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- 2. Mô tả Thương Hiệu --}}
                            <div class="form-group">
                                <label for="brand_product_desc">Mô tả thương hiệu</label>
                                <textarea class="form-control" name="brand_product_desc" id="brand_product_desc" placeholder="Mô tả thương hiệu">{{ old('brand_product_desc', $edit_brand_product->brand_desc) }}</textarea>

                                {{-- Hiển thị lỗi ngay bên dưới --}}
                                @error('brand_product_desc')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <button type="submit" name="update_brand_product" class="btn btn-info">Cập Nhật Thương
                                Hiệu</button>
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
