@extends('admin_layout')
@section('admin_content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    Thêm mã giảm giá
                </header>
                <div class="panel-body">
                    <?php
                    $message = session('message');
                    if ($message) {
                        echo '<span class="text-alert">' . $message . '</span>';
                        session()->forget('message');
                    } ?>
                    <div class="position-center">
                        <form role="form" action="{{ URL::to('/insert-coupon-code') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="exampleInputEmail1">Tên mã giảm giá</label>
                                <input type="text" name="coupon_name" class="form-control" id="exampleInputEmail1">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Mã giảm giá</label>
                                <textarea class="form-control" name="coupon_code" id="exampleInputPassword1"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Số lượng mã</label>
                                <textarea class="form-control" name="coupon_time" id="exampleInputPassword1"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Tính năng mã </label>
                                <select name="coupon_condition" class="form-control input-sm m-bot15">
                                    <option value="0">-----chọn----</option>
                                    <option value="1">giảm theo phần trăm </option>
                                    <option value="2">giảm theo tiền</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Nhập số phần trăm hoặc số tiền </label>
                                <textarea class="form-control" name="coupon_number" id="exampleInputPassword1"></textarea>
                            </div>
                            <button type="submit" name="add_coupon" class="btn btn-info">Thêm mã</button>
                        </form>
                    </div>

                </div>
            </section>
        </div>
    @endsection
