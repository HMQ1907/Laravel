@extends('layout.app')
<style>
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type=number] {
        -moz-appearance: textfield;
    }
</style>
@section('content')
    <div class="container">
        <h3 class="text-left">Sản phẩm</h3>
        <div class="info_product row mb-5">
            <div class="input-group col-md-3">
                <div class="col-auto">
                    <label for="product_name">Tên sản phẩm</label>
                    <input id="product_name" type="text" class="form-control" name="product_name"
                        placeholder="Nhâp tên sản phẩm" aria-label="Username" aria-describedby="basic-addon1">
                </div>
            </div>
            <div class="input-group col-md-3">
                <div class="col-auto">
                    <label for="product_status">Trạng thái</label>
                    <select name="product_status" class="form-control" id="product_status">
                        <option value="">Trạng thái</option>
                        <option value="1">Đang bán</option>
                        <option value="0">Hết hàng</option>
                    </select>
                </div>
            </div>
            <div class="input-group col-md-3">
                <div class="col-auto">
                    <label for="min_price">Giá bán từ</label>
                    <input type="number" class="form-control" id="min_price" name="min_price" min="0"
                        value="0">
                </div>
            </div>

            <div class="input-group col-md-3">
                <div class="col-auto">
                    <label for="max_price">Giá bán đến</label>
                    <input type="number" class="form-control" id="max_price" name="max_price" min="0"
                        value="0">
                </div>
            </div>
        </div>

        <div class="info_product d-flex flex-row">
            <div style="margin-right: 645px" class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text mr-1" id="basic-addon1"><i class="fa fa-user-plus"
                            aria-hidden="true"></i></span>
                </div>
                <a href="{{ route('product.add') }}"><button id="addProductBtn" class="btn btn-primary">Thêm
                        mới</button></a>
            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text mr-1" id="basic-addon1"><i class="fa fa-search"
                            aria-hidden="true"></i></span>
                </div>
                <button type="button" id="search-product" class="btn btn-success">Tìm kiếm</button>
            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text mr-1" id="basic-addon1"><i class="fa fa-times" aria-hidden="true"></i>
                    </span>
                </div>
                <button class="btn btn-danger">Xóa tìm</button>
            </div>
        </div>

        {{-- Start Table --}}
        @include('product.table')
    </div>
@endsection

@push('head')
    <script src="{{ asset('js/handle_products.js') }}"></script>
    <script>
        let search_url = "{{ route('product.index') }}";
    </script>
@endpush
