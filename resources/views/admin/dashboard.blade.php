@extends('admin_layout')
@section('admin_content')
    <div class="container">
        <h3 class="text-center mb-4">Thống kê doanh số</h3>
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow border-0 rounded-4">
                    <div class="card-header bg-light">
                        <i class="fa fa-filter"></i> Lọc theo thời gian
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ URL::to('/dashboard') }}" class="form-inline">
                            <div class="form-group mr-3 mb-2">
                                <label for="start_date" class="mr-2">Từ ngày:</label>
                                <input type="date" class="form-control" id="start_date" name="start_date"
                                    value="{{ $start_date ?? '' }}">
                            </div>
                            <div class="form-group mr-3 mb-2">
                                <label for="end_date" class="mr-2">Đến ngày:</label>
                                <input type="date" class="form-control" id="end_date" name="end_date"
                                    value="{{ $end_date ?? '' }}">
                            </div>
                            <button type="submit" class="btn btn-primary mb-2 mr-2"><i class="fa fa-search"></i>
                                Lọc</button>
                            <a href="{{ URL::to('/dashboard') }}" class="btn btn-secondary mb-2"><i
                                    class="fa fa-refresh"></i> Reset</a>
                        </form>
                        @if ($start_date && $end_date)
                            <p class="mt-2 text-info">
                                Đang hiển thị thống kê từ
                                <strong>{{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }}</strong>
                                đến <strong>{{ \Carbon\Carbon::parse($end_date)->format('d/m/Y') }}</strong>.
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row text-center mb-4" style="margin-top: 20px">
            <div class="col-md-6">
                <div class="card bg-primary text-white p-3 shadow">
                    <h4>Tổng doanh thu</h4>
                    <h2>{{ number_format($totalRevenue, 0, ',', '.') }} VNĐ</h2>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-success text-white p-3 shadow">
                    <h4 style="color: #fff">Tổng đơn hàng</h4>
                    <h2 style="color: #fff">{{ $totalOrders }}</h2>
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
            // Use the chart-ready monthly data from the controller
            const monthlyData = {!! json_encode($chartMonthlyRevenue) !!};

            const chartData = Object.keys(monthlyData).map(month => ({
                month: parseInt(month),
                revenue: monthlyData[month]
            }));

            // Clear previous chart if it exists to prevent duplicates on refresh
            $('#revenue-morris-chart').empty();

            if (chartData.length > 0) {
                Morris.Line({
                    element: 'revenue-morris-chart',
                    data: chartData,
                    xkey: 'month',
                    ykeys: ['revenue'],
                    labels: ['Doanh thu (VNĐ)'],
                    lineColors: ['#4e73df'],
                    hideHover: 'auto',
                    resize: true,
                    parseTime: false, // Important: Treat 'month' as a simple number
                    gridTextSize: 12,
                    xLabelAngle: 0,
                    xLabelFormat: function(x) {
                        return 'Tháng ' + x.src.month; // Access the month property correctly
                    },
                    yLabelFormat: function(y) {
                        // Check if y is a valid number before formatting
                        if (typeof y === 'number' && !isNaN(y)) {
                            return y.toLocaleString('vi-VN') + ' ₫';
                        }
                        return ''; // Return empty string if not a valid number
                    }
                });
            } else {
                $('#revenue-morris-chart').html(
                    '<p class="text-center text-muted">Không có dữ liệu doanh thu cho khoảng thời gian này.</p>'
                    );
            }

            // Style adjustments (optional)
            setTimeout(() => {
                document.querySelectorAll('#revenue-morris-chart text').forEach(el => {
                    el.style.fill = '#666'; // Adjust color if needed
                    el.style.fontWeight = '500';
                    el.style.fontSize = '12px';
                });
            }, 200);
        });
    </script>
@endsection
