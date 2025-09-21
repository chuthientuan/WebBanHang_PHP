<div class="col-sm-3">
    <div class="left-sidebar">
        <h2>Danh mục sản phẩm</h2>
        <div class="panel-group category-products" id="accordian"><!--category-productsr-->
            @foreach ($category as $key => $cate)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title"><a
                                href="{{ URL::to('/danh-muc-san-pham/' . $cate->category_id) }}">{{ $cate->category_name }}</a>
                        </h4>
                    </div>
                </div>
            @endforeach
        </div><!--/category-products-->

        <div class="brands_products"><!--brands_products-->
            <h2>Thương hiệu sản phẩm</h2>
            <div class="brands-name">
                <ul class="nav nav-pills nav-stacked">
                    @foreach ($brand as $key => $brand)
                        <li><a href="{{ URL::to('/thuong-hieu-san-pham/' . $brand->brand_id) }}">
                                <span class="pull-right">(50)</span>{{ $brand->brand_name }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div><!--/brands_products-->

    </div>
</div>
