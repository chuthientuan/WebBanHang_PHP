@extends('admin_layout')
@section('admin_content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    Thêm Vận chuyển
                </header>
                <div class="panel-body">
                    <?php
                    $message = session('message');
                    if ($message) {
                        echo '<span class="text-alert">' . $message . '</span>';
                        session()->forget('message');
                    } ?>
                    <div class="position-center">
                        <form >
                            @csrf
                            <div class="form-group">
                                <label for="exampleInputPassword1">Chọn thành phố </label>
                                <select name="city" id="city" class="form-control input-sm m-bot15 choose city" >
                                    <option value="">----Chọn tỉnh thành phố----</option>
                                    @foreach ( $city as $key => $ci )
                                        <option value="{{$ci->matp}}">{{$ci->name_city}}</option>
                                    @endforeach
                                 
                                </select>
                            </div>
                             <div class="form-group">
                                <label for="exampleInputPassword1">Chọn quận huyện </label>
                                <select name="province" id="province" class="form-control input-sm m-bot15  province choose">
                                    <option value="">----chọn quận huyện----</option>
                                    
                                </select>
                            </div>
                             <div class="form-group">
                                <label for="exampleInputPassword1">Chọn xã phường  </label>
                                <select name="wards" id="wards" class="form-control input-sm m-bot15 wards">
                                    <option value="">-- --chọn xã phường----</option>
                                   
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Phí vận chuyển </label>
                                <textarea class="form-control" name="free_ship" id="exampleInputPassword1" placeholder="Mô tả danh mục"></textarea>
                            </div>

                            <button type="button" name="add_delivery" class="btn btn-info add_delivery">Thêm phí vận chuyển </button>
                        </form>
                    </div>

                </div>
            </section>

        </div>
    @endsection
