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
                Liệt kê đơn hàng
            </div>
            <div class="row w3-res-tb">

            </div>
            <div class="table-responsive">
                <table class="table table-striped b-t b-light">
                    <thead>
                        <tr>
                            <th>Thứ Tự</th>
                            <th>Mã Đơn</th>
                            <th>Ngày đặt hàng</th>
                            <th>Tình Trạng Đơn Hàng</th>
                            <th style="width:30px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 0;
                        @endphp
                        @foreach ($order as $key => $ord)
                            @php
                                $i++;
                            @endphp
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $ord->order_code }}</td>
                                <td>{{ $ord->created_at }}</td>
                                <td>
                                    @if ($ord->order_status == 1)
                                        Đơn hàng mới
                                    @else
                                        Đã xử lý
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ URL::to('/view-order/' . $ord->order_id) }}" class="active styling-edit"
                                        ui-toggle-class="">
                                        <i class="fa fa-eye text-success text-active"></i>
                                    </a>
                                    <br>
                                    <a onclick="return confirm('Bạn có chắc là muốn xóa danh mục này không?')"
                                        href="{{ URL::to('/delete-order/' . $ord->order_id) }}" class="active styling-edit"
                                        ui-toggle-class="">
                                        <i class="fa fa-times text-danger text"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
