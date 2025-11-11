@extends('admin_layout')
@section('admin_content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    Thêm Danh Mục Sản Phẩm
                </header>
                <div class="panel-body">

                    {{-- Hiển thị thông báo thành công (nếu có) --}}
                    @if (session('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                        {{ session()->forget('message') }}
                    @endif

                    {{-- Bỏ khối hiển thị lỗi chung @if ($errors->any()) ở đây --}}

                    <div class="position-center">
                        <form role="form" action="{{ URL::to('/save-category-product') }}" method="post">
                            {{ csrf_field() }}

                            {{-- 1. Tên Danh Mục --}}
                            <div class="form-group">
                                <label for="category_product_name">Tên Danh Mục</label>
                                <input type="text" name="category_product_name" class="form-control"
                                    id="category_product_name" placeholder="Tên Danh Mục"
                                    value="{{ old('category_product_name') }}">
                                {{-- Hiển thị lỗi ngay bên dưới --}}
                                @error('category_product_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- 2. Mô tả Danh Mục --}}
                            <div class="form-group">
                                <label for="category_product_desc">Mô tả danh mục</label>
                                <textarea class="form-control" name="category_product_desc" id="category_product_desc" placeholder="Mô tả danh mục">{{ old('category_product_desc') }}</textarea>
                                {{-- Hiển thị lỗi ngay bên dưới --}}
                                @error('category_product_desc')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- 3. Hiển thị --}}
                            <div class="form-group">
                                <label for="category_product_status">Hiển thị</label>
                                <select name="category_product_status" id="category_product_status"
                                    class="form-control input-sm m-bot15">
                                    {{-- Dùng old() để chọn lại giá trị cũ --}}
                                    <option value="0" {{ old('category_product_status') == '0' ? 'selected' : '' }}>Ẩn
                                    </option>
                                    <option value="1" {{ old('category_product_status') == '1' ? 'selected' : '' }}>
                                        Hiển thị</option>
                                </select>
                                @error('category_product_status')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <button type="submit" name="add_category_product" class="btn btn-info">Thêm Danh Mục</button>
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
