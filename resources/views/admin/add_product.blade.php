@extends('admin_layout')
@section('admin_content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    Thêm Sản Phẩm
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
                        <form role="form" action="{{ URL::to('/save-product') }}" method="post"
                            enctype="multipart/form-data">
                            {{ csrf_field() }}

                            {{-- 1. Tên Sản Phẩm --}}
                            <div class="form-group">
                                <label for="product_name">Tên Sản Phẩm</label>
                                <input type="text" name="product_name" class="form-control" id="product_name"
                                    placeholder="Nhập tên sản phẩm" value="{{ old('product_name') }}">
                                @error('product_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- 2. Giá Nhập --}}
                            <div class="form-group">
                                <label for="product_import_price">Giá Nhập</label>
                                <input type="text" name="product_import_price" class="form-control"
                                    id="product_import_price" placeholder="Nhập giá nhập kho"
                                    value="{{ old('product_import_price') }}">
                                @error('product_import_price')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- 3. Giá Bán --}}
                            <div class="form-group">
                                <label for="product_price">Giá Bán Sản Phẩm</label>
                                <input type="text" name="product_price" class="form-control" id="product_price"
                                    value="{{ old('product_price') }}" readonly>
                                @error('product_price')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- 4. Hình Ảnh Sản Phẩm --}}
                            <div class="form-group">
                                <label for="product_image">Hình Ảnh Sản Phẩm</label>
                                <input type="file" name="product_image" class="form-control" id="product_image">
                                @error('product_image')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- 5. Mô tả Sản Phẩm --}}
                            <div class="form-group">
                                <label for="product_desc">Mô tả Sản Phẩm</label>
                                <textarea class="form-control" name="product_desc" placeholder="Mô tả sản phẩm" id="ckeditor1">{{ old('product_desc') }}</textarea>
                                @error('product_desc')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="product_slbd">Số Lượng Sản Phẩm</label>
                                <input type="number" name="product_slbd" class="form-control" id="product_slbd"
                                    placeholder="Điền số lượng" value="{{ old('product_slbd') }}" min="1">
                                @error('product_slbd')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- 8. Danh Mục Sản Phẩm --}}
                            <div class="form-group">
                                <label for="product_cate">Danh Mục Sản Phẩm</label>
                                <select name="product_cate" id="product_cate" class="form-control input-sm m-bot15">
                                    <option value="">-- Chọn Danh Mục --</option>
                                    @foreach ($cate_product as $key => $cate)
                                        <option value="{{ $cate->category_id }}"
                                            {{ old('product_cate') == $cate->category_id ? 'selected' : '' }}>
                                            {{ $cate->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- 9. Thương Hiệu --}}
                            <div class="form-group">
                                <label for="product_brand">Thương Hiệu</label>
                                <select name="product_brand" id="product_brand" class="form-control input-sm m-bot15">
                                    <option value="">-- Chọn Thương Hiệu --</option>
                                    @foreach ($brand_product as $key => $brand)
                                        <option value="{{ $brand->brand_id }}"
                                            {{ old('product_brand') == $brand->brand_id ? 'selected' : '' }}>
                                            {{ $brand->brand_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- 10. Hiển thị --}}
                            <div class="form-group">
                                <label for="product_status">Hiển thị</label>
                                <select name="product_status" id="product_status" class="form-control input-sm m-bot15">
                                    <option value="0" {{ old('product_status') == '0' ? 'selected' : '' }}>Ẩn</option>
                                    <option value="1" {{ old('product_status') == '1' ? 'selected' : '' }}>Hiển thị
                                    </option>
                                </select>
                                @error('product_status')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <button type="submit" name="add_product" class="btn btn-info">Thêm Sản Phẩm</button>
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

@section('scripts')
    {{-- Script tự động tính giá bán --}}
    <script>
        $(document).ready(function() {
            // Định nghĩa tỷ lệ lợi nhuận (ví dụ: 1.3 = lãi 30%)
            const PROFIT_MARGIN = 1.3;

            // Hàm tính toán và cập nhật giá bán
            function calculateSellingPrice() {
                // 1. Lấy giá trị nhập vào
                let importPrice = $('#product_import_price').val();

                // 2. Xóa các ký tự không phải số (như dấu phẩy, chữ) để tính toán
                importPrice = parseFloat(importPrice.replace(/[^0-9]/g, ''));

                if (!isNaN(importPrice)) {
                    // 3. Tính giá bán
                    let sellingPrice = importPrice * PROFIT_MARGIN;

                    // 4. Làm tròn đến hàng nghìn gần nhất
                    sellingPrice = Math.round(sellingPrice / 1000) * 1000;

                    // 5. Cập nhật giá trị vào ô Giá Bán
                    $('#product_price').val(sellingPrice);
                } else {
                    // Nếu giá nhập không hợp lệ, xóa ô giá bán
                    $('#product_price').val('');
                }
            }

            // Lắng nghe sự kiện 'input' (gõ phím) trên ô Giá Nhập
            $('#product_import_price').on('input', calculateSellingPrice);

            // Chạy hàm tính toán 1 lần khi tải trang
            // để xử lý trường hợp quay lại trang (với old() data)
            calculateSellingPrice();
        });
    </script>
@endsection
