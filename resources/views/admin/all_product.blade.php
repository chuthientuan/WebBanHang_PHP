@extends('admin_layout')
@section('admin_content')
    <div class="table-agile-info">
        <div class="panel panel-default">
            <?php
            $message = session('message');
            if ($message) {
                echo '<span class="text-alert" >' . $message . '</span>';
                session()->forget('message');
            } ?>
            <div class="panel-heading">
                Liệt kê sản phẩm
            </div>
            <div class="table-responsive">
                <table class="table table-striped b-t b-light">
                    <thead>
                        <tr>
                            <th>Tên Sản Phẩm </th>
                            <th>Giá Nhập</th>
                            <th>Giá Bán</th>
                            <th>Hình Sản Phẩm </th>
                            <th>Số Lượng Nhập</th>
                            <th>Số Lượng Tồn</th>
                            <th>Danh Mục </th>
                            <th>Thương Hiệu </th>
                            <th>Hiển thị</th>
                            <th style="width:30px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($all_product as $key => $pro)
                            <tr>
                                <td>{{ $pro->product_name }}</td>
                                <td>{{ number_format($pro->product_import_price, 0, ',', '.') }} đ</td>
                                <td>{{ number_format($pro->product_price, 0, ',', '.') }} đ</td>
                                <td><img src="public/uploads/product/{{ $pro->product_image }}" height="100" width="100">
                                </td>
                                <td>{{ $pro->product_slbd }}</td>
                                <td>{{ $pro->product_quantity }}</td>
                                <td>{{ $pro->category->category_name }}</td>
                                <td>{{ $pro->brand->brand_name }}</td>
                                <td><span class="text-ellipsis">
                                        <?php
                                            if($pro->product_status==0){
                                                ?>
                                        <a href="{{ URL::to('/unactive-product/' . $pro->product_id) }}"><span
                                                class=" fa-thumb-syling fa fa-thumbs-down"></span></a>;
                                        <?php
                                            }else{
                                                ?>
                                        <a href="{{ URL::to('/active-product/' . $pro->product_id) }}"><span
                                                class=" fa-thumb-syling fa fa-thumbs-up"></span></a>;
                                        <?php
                                            }
                                        ?>
                                    </span></td>
                                <td>
                                    <a href="{{ URL::to('/edit-product/' . $pro->product_id) }}"
                                        class="active styling-edit" ui-toggle-class="">
                                        <i class="fa fa-pencil-square-o text-success text-active"></i>
                                    </a>
                                    <a onclick="return confirm('Bạn có chắc là muốn xóa sản phẩm này không?')"
                                        href="{{ URL::to('/delete-product/' . $pro->product_id) }}"
                                        class="active styling-edit" ui-toggle-class="">
                                        <i class="fa fa-times text-danger text"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <footer class="panel-footer">
                <div class="row">
                    <div class="col-sm-5 text-center">
                        {{-- Hiển thị thông tin số lượng sản phẩm --}}
                        <small class="text-muted inline m-t-sm m-b-sm">
                            Hiển thị {{ $all_product->firstItem() }} - {{ $all_product->lastItem() }} trên tổng số
                            {{ $all_product->total() }} sản phẩm
                        </small>
                    </div>
                    <div class="col-sm-7 text-right text-center-xs">
                        {{-- Tự động hiển thị các nút phân trang --}}
                        {!! $all_product->links() !!}
                    </div>
                </div>
            </footer>
        </div>
    </div>
@endsection
