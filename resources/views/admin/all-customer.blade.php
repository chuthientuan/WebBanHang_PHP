@extends('admin_layout')
@section('admin_content')
    <div class="table-agile-info">
        <div class="panel panel-default">
            <div class="panel-heading">
                Liệt kê người dùng
            </div>
            <div class="row w3-res-tb">

            </div>
            <div class="table-responsive">
                <table class="table table-striped b-t b-light">
                    <thead>
                        <tr>
                            <th>Thứ Tự</th>
                            <th>Họ Tên</th>
                            <th>Email</th>
                            <th>Số Điện Thoại</th>
                            <th style="width:30px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 0;
                        @endphp
                        @foreach ($customer as $key => $cus)
                            @php
                                $i++;
                            @endphp
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $cus->customer_name }}</td>
                                <td>{{ $cus->customer_email }}</td>
                                <td>{{ $cus->customer_phone }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
