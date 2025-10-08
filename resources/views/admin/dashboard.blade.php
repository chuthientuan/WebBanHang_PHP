@extends('admin_layout')
@section('admin_content')
    <div class="container">
        <h3 class="text-center mb-4">Thống kê doanh số</h3>

        <div class="row text-center mb-4" style="margin-top: 20px">
            <div class="col-md-6">
                <div class="card bg-primary text-white p-3 shadow">
                    <h4>Tổng doanh thu</h4>
                    <h2>{{ number_format($totalRevenue, 0, ',', '.') }} VNĐ</h2>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-success text-white p-3 shadow">
                    <h4>Tổng đơn hàng</h4>
                    <h2>{{ $totalOrders }}</h2>
                </div>
            </div>
        </div>

        <div class="card shadow border-0 rounded-4 mb-5" style="margin-top: 20px">
            <div class="card-header bg-primary text-white fw-bold">
                <i class="fa fa-chart-area"></i> Biểu đồ doanh thu theo tháng
            </div>
            <div class="card-body">
                <div id="revenue-morris-chart" style="height: 300px;"></div>
            </div>
        </div>

        <div class="table-agile-info" style="margin-top: 12px">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Top 5 sản phẩm bán chạy
                </div>
                <div class="row w3-res-tb">

                </div>
                <div class="table-responsive">
                    <table class="table table-striped b-t b-light">
                        <thead>
                            <tr>
                                <th>Thứ Tự</th>
                                <th>Tên sản phẩm</th>
                                <th>Số lượng bán</th>
                                <th style="width:30px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 0;
                            @endphp
                            @foreach ($topProducts as $name => $quantity)
                                @php
                                    $i++;
                                @endphp
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ $name }}</td>
                                    <td>{{ $quantity }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- Chart.js --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const monthlyData = {!! json_encode($monthlyRevenue) !!};

            const chartData = Object.keys(monthlyData).map(month => ({
                month: parseInt(month),
                revenue: monthlyData[month]
            }));

            Morris.Line({
                element: 'revenue-morris-chart',
                data: chartData,
                xkey: 'month',
                ykeys: ['revenue'],
                labels: ['Doanh thu (VNĐ)'],
                lineColors: ['#4e73df'],
                hideHover: 'auto',
                resize: true,
                parseTime: false, // ⚡ Bắt buộc nếu xkey không phải là date
                gridTextSize: 12,
                xLabelAngle: 0,
                xLabelFormat: function(x) {
                    return 'Tháng ' + x.src.month;
                },
                yLabelFormat: function(y) {
                    return y.toLocaleString('vi-VN') + ' ₫';
                }
            });
        });
    </script>
@endsection
