@extends('index')
@section('content')
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
                        @foreach ($orders as $key => $ord)
                            @php
                                $i++;
                            @endphp
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $ord->order_code }}</td>
                                <td>{{ $ord->created_at }}</td>
                                <td>
                                    @if ($ord->order_status == 1)
                                        Đang chờ xử lý
                                    @elseif($ord->order_status == 2)
                                        Đang giao hàng
                                    @elseif($ord->order_status == 3)
                                        Đã giao hàng
                                    @else
                                        Đã hủy
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ URL::to('/view-history-order/' . $ord->order_id) }}"
                                        class="active styling-edit" ui-toggle-class="">Xem đơn hàng
                                    </a>
                                    @if ($ord->order_status == 1)
                                        <a onclick="return confirm('Bạn có chắc là muốn hủy đơn hàng này không?')"
                                            href="{{ URL::to('/cancel-order/' . $ord->order_id) }}"
                                            class="active styling-edit" style="color:red; margin-left: 10px;">
                                            Hủy đơn
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
