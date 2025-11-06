@extends('admin_layout')
@section('admin_content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    Thêm Thương Hiệu Sản Phẩm
                </header>
                <div class="panel-body">

                    {{-- Hiển thị thông báo thành công (nếu có) --}}
                    @if (session('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                        {{ session()->forget('message') }}
                    @endif

                    {{-- Bỏ khối @if ($errors->any()) ... @endif cũ ở đây --}}

                    <div class="position-center">
                        <form role="form" action="{{ URL::to('/save-brand-product') }}" method="post">
                            {{ csrf_field() }}

                            {{-- 1. Tên Thương Hiệu --}}
                            <div class="form-group">
                                <label for="brand_product_name">Tên Thương Hiệu</label>
                                <input type="text" name="brand_product_name" class="form-control" id="brand_product_name"
                                    placeholder="Tên thương hiệu" value="{{ old('brand_product_name') }}">
                                {{-- Hiển thị lỗi ngay bên dưới --}}
                                @error('brand_product_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- 2. Mô tả Thương Hiệu --}}
                            <div class="form-group">
                                <label for="brand_product_desc">Mô tả thương hiệu</label>
                                <textarea class="form-control" name="brand_product_desc" id="brand_product_desc" placeholder="Mô tả thương hiệu">{{ old('brand_product_desc') }}</textarea>
                                {{-- Hiển thị lỗi ngay bên dưới --}}
                                @error('brand_product_desc')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- 3. Hiển thị --}}
                            <div class="form-group">
                                <label for="brand_product_status">Hiển thị</label>
                                <select name="brand_product_status" id="brand_product_status"
                                    class="form-control input-sm m-bot15">
                                    {{-- Dùng old() để chọn lại giá trị cũ --}}
                                    <option value="0" {{ old('brand_product_status') == '0' ? 'selected' : '' }}>Ẩn
                                    </option>
                                    <option value="1" {{ old('brand_product_status') == '1' ? 'selected' : '' }}>Hiển
                                        thị</option>
                                </select>
                                @error('brand_product_status')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <button type="submit" name="add_brand_product" class="btn btn-info">Thêm Thương Hiệu</button>
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
