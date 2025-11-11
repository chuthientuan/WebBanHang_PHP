@extends('admin_layout')
@section('admin_content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    Cập Nhật Sản Phẩm
                </header>
                <div class="panel-body">

                    {{-- Hiển thị thông báo thành công (nếu có) --}}
                    @if (session('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                        {{ session()->forget('message') }}
                    @endif

                    {{-- Bỏ khối lỗi chung @if ($errors->any()) --}}

                    <div class="position-center">
                        @foreach ($edit_product as $key => $pro)
                            <form role="form" action="{{ URL::to('/update-product/' . $pro->product_id) }}" method="post"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}

                                {{-- 1. Tên Sản Phẩm --}}
                                <div class="form-group">
                                    <label for="product_name">Tên Sản Phẩm</label>
                                    <input type="text" name="product_name" class="form-control" id="product_name"
                                        value="{{ old('product_name', $pro->product_name) }}">
                                    @error('product_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- 2. Giá Sản Phẩm --}}
                                <div class="form-group">
                                    <label for="product_price">Giá Sản Phẩm</label>
                                    <input type="text" name="product_price" class="form-control" id="product_price"
                                        value="{{ old('product_price', $pro->product_price) }}">
                                    @error('product_price')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- 3. Hình Ảnh Sản Phẩm --}}
                                <div class="form-group">
                                    <label for="product_image">Hình Ảnh Sản Phẩm</label>
                                    <input type="file" name="product_image" class="form-control" id="product_image">
                                    <img src="{{ URL::to('public/Uploads/product/' . $pro->product_image) }}" alt=""
                                        height="100" width="100" style="margin-top: 10px;">
                                    @error('product_image')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- 4. Mô tả Sản Phẩm --}}
                                <div class="form-group">
                                    <label for="product_desc">Mô tả Sản Phẩm</label>
                                    <textarea class="form-control" name="product_desc" id="ckeditor2">{{ old('product_desc', $pro->product_desc) }}</textarea>
                                    @error('product_desc')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- 6. Số Lượng Sản Phẩm --}}
                                <div class="form-group">
                                    <label for="product_quantity">Số Lượng Sản Phẩm</label>
                                    <input type="number" name="product_quantity" class="form-control" id="product_quantity"
                                        value="{{ old('product_quantity', $pro->product_quantity) }}" min="0">
                                    @error('product_quantity')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- 7. Danh Mục Sản Phẩm --}}
                                <div class="form-group">
                                    <label for="product_cate">Danh Mục Sản Phẩm</label>
                                    <select name="product_cate" id="product_cate" class="form-control input-sm m-bot15">
                                        <option value="">-- Chọn Danh Mục --</option>
                                        @foreach ($cate_product as $key => $cate)
                                            <option value="{{ $cate->category_id }}" {{-- Logic old() kết hợp với giá trị DB --}}
                                                {{ old('product_cate', $pro->category_id) == $cate->category_id ? 'selected' : '' }}>
                                                {{ $cate->category_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('product_cate')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- 8. Thương Hiệu --}}
                                <div class="form-group">
                                    <label for="product_brand">Thương Hiệu</label>
                                    <select name="product_brand" id="product_brand" class="form-control input-sm m-bot15">
                                        <option value="">-- Chọn Thương Hiệu --</option>
                                        @foreach ($brand_product as $key => $brand)
                                            <option value="{{ $brand->brand_id }}" {{-- Logic old() kết hợp với giá trị DB --}}
                                                {{ old('product_brand', $pro->brand_id) == $brand->brand_id ? 'selected' : '' }}>
                                                {{ $brand->brand_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('product_brand')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- 9. Hiển thị --}}
                                <div class="form-group">
                                    <label for="product_status">Hiển thị</label>
                                    <select name="product_status" id="product_status" class="form-control input-sm m-bot15">
                                        <option value="0"
                                            {{ old('product_status', $pro->product_status) == 0 ? 'selected' : '' }}>Ẩn
                                        </option>
                                        <option value="1"
                                            {{ old('product_status', $pro->product_status) == 1 ? 'selected' : '' }}>Hiển
                                            thị</option>
                                    </select>
                                    @error('product_status')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <button type="submit" name="update_product" class="btn btn-info">Cập nhập sản phẩm</button>
                            </form>
                        @endforeach
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
