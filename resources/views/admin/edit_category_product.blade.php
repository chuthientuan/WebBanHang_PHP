@extends('admin_layout')
@section('admin_content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    Cập Nhật Danh Mục Sản Phẩm
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
                        <form role="form"
                            action="{{ URL::to('/update-category-product/' . $edit_category_product->category_id) }}"
                            method="post">
                            {{ csrf_field() }}

                            {{-- 1. Tên Danh Mục --}}
                            <div class="form-group">
                                <label for="category_product_name">Tên Danh Mục</label>
                                <input type="text" {{-- Dùng old() để ưu tiên hiển thị giá trị cũ nếu validation fail --}}
                                    value="{{ old('category_product_name', $edit_category_product->category_name) }}"
                                    name="category_product_name" class="form-control" id="category_product_name"
                                    placeholder="Tên Danh Mục">

                                {{-- Hiển thị lỗi ngay bên dưới --}}
                                @error('category_product_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- 2. Mô tả Danh Mục --}}
                            <div class="form-group">
                                <label for="category_product_desc">Mô tả danh mục</label>
                                <textarea class="form-control" name="category_product_desc" id="category_product_desc">{{ old('category_product_desc', $edit_category_product->category_desc) }}</textarea>

                                {{-- Hiển thị lỗi ngay bên dưới --}}
                                @error('category_product_desc')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <button type="submit" name="update_category_product" class="btn btn-info">Cập Nhật Danh
                                Mục</button>
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
