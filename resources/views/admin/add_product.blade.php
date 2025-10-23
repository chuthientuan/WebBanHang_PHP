@extends('admin_layout')
@section('admin_content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    Thêm Sản Phẩm
                </header>
                <div class="panel-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <?php
                    $message = session('message');
                    if ($message) {
                        echo '<span class="text-alert">' . $message . '</span>';
                        session()->forget('message');
                    } ?>
                    <div class="position-center">
                        <form role="form" action="{{ URL::to('/save-product') }}" method="post"
                            enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="exampleInputEmail1">Tên Sản Phẩm </label>
                                <input type="text" name="product_name" class="form-control" id="exampleInputEmail1"
                                    placeholder="Tên Danh Mục ">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Giá Sản Phẩm </label>
                                <input type="text" name="product_price" class="form-control" id="exampleInputEmail1"
                                    placeholder="Tên Danh Mục ">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Hình Ảnh Sản Phẩm </label>
                                <input type="file" name="product_image" class="form-control" id="exampleInputEmail1">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputPassword1">Mô tả Sản Phẩm</label>
                                <textarea class="form-control" name="product_desc" placeholder="Mô tả sản phẩm" id="ckeditor1"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputPassword1">Nội Dung Sản Phẩm</label>
                                <textarea class="form-control" name="product_content" id="exampleInputPassword1" placeholder="Mô tả  nội dung danh mục"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputPassword1">Số Lượng Sản Phẩm</label>
                                <input type="number" name="product_quantity" class="form-control" id="exampleInputEmail1"
                                    placeholder="Điền số lượng">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputPassword1">Danh Mục Sản Phẩm </label>
                                <select name="product_cate" class="form-control input-sm m-bot15">
                                    @foreach ($cate_product as $key => $cate)
                                        <option value="{{ $cate->category_id }}">{{ $cate->category_name }}</option>
                                    @endforeach

                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputPassword1">Thương Hiệu</label>
                                <select name="product_brand" class="form-control input-sm m-bot15">
                                    @foreach ($brand_product as $key => $brand)
                                        <option value="{{ $brand->brand_id }}">{{ $brand->brand_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputPassword1">Hiển thị</label>
                                <select name="product_status" class="form-control input-sm m-bot15">
                                    <option value="0">Ẩn</option>
                                    <option value="1">Hiển thị</option>
                                </select>
                            </div>

                            <button type="submit" name="add_product" class="btn btn-info">Thêm Sản Phẩm</button>
                        </form>
                    </div>

                </div>
            </section>

        </div>
    @endsection
