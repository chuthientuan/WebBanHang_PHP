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
            <div class="row w3-res-tb" style="margin-bottom: 10px; padding: 10px;">
                <div class="col-sm-3 m-b-xs">
                    {{-- Form lọc --}}
                    <form method="GET" action="{{ URL::to('/manage-order') }}">
                        <div class="input-group">
                            <select name="status" class="input-sm form-control w-sm inline v-middle"
                                onchange="this.form.submit()">
                                <option value="all">-- Tất cả trạng thái --</option>
                                <option value="1" {{ request()->input('status') == '1' ? 'selected' : '' }}>Đang chờ xử
                                    lý</option>
                                <option value="2" {{ request()->input('status') == '2' ? 'selected' : '' }}>Đang giao
                                    hàng</option>
                                <option value="3" {{ request()->input('status') == '3' ? 'selected' : '' }}>Đã giao
                                    hàng</option>
                                <option value="4" {{ request()->input('status') == '4' ? 'selected' : '' }}>Đã hủy
                                </option>
                            </select>
                            {{-- Nút reset bộ lọc --}}
                            <span class="input-group-btn">
                                <a href="{{ URL::to('/manage-order') }}" class="btn btn-sm btn-default">Reset</a>
                            </span>
                        </div>
                    </form>
                </div>
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
                        @foreach ($all_order as $key => $ord)
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
            <footer class="panel-footer">
                <div class="row">
                    <div class="col-sm-5 text-center">
                        <small class="text-muted inline m-t-sm m-b-sm">
                            Hiển thị {{ $all_order->firstItem() }} - {{ $all_order->lastItem() }} trên tổng số
                            {{ $all_order->total() }} đơn hàng
                        </small>
                    </div>
                    <div class="col-sm-7 text-right text-center-xs">
                        {{-- CẬP NHẬT: Thêm appends để giữ bộ lọc khi chuyển trang --}}
                        {!! $all_order->appends(request()->all())->links() !!}
                    </div>
                </div>
            </footer>
        </div>
    </div>
@endsection
