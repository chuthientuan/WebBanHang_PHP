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
                                        <a href="{{ URL::to('/cancel-order/' . $ord->order_id) }}"
                                            class="active styling-edit cancel-order" style="color:red; margin-left: 10px;">
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
@section('scripts2')
    {{-- Đảm bảo bạn đã tải jQuery và SweetAlert2 trong layout chính (index.blade.php) --}}

    <script type="text/javascript">
        $(document).ready(function() {

            $('.cancel-order').on('click', function(e) {
                e.preventDefault(); // Ngăn chặn link chuyển trang ngay lập tức
                var deleteUrl = $(this).attr('href'); // Lấy đường dẫn xóa

                Swal.fire({
                    title: 'Bạn có chắc không?',
                    text: "Bạn xác nhận hủy đơn hàng này không?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Xác nhận',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Nếu người dùng đồng ý, chuyển hướng đến link xóa
                        window.location.href = deleteUrl;
                    }
                });
            });
        });
    </script>
@endsection
