@extends('layout.app')
@section('content')
    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-md-7">
                <h4> {{ $title }} </h4>
                <div class="form-group">
                    <label for="productName">Tên sản phẩm</label>
                    <input value="{{ isset($product) ? $product->product_name : '' }}" type="text" name="product_name"
                        type="text" class="form-control" id="product_name" placeholder="Nhập tên sản phẩm" required>
                    <div class="invalid-feedback">Tên sản phẩm không được để trống</div>
                    <span id="pro_name_error" class="text-danger"></span>
                </div>
                <div class="form-group">
                    <label for="price">Giá bán</label>
                    <input value="{{ isset($product) ? number_format($product->product_price) : '' }}" type="number"
                        class="form-control" id="product_price" placeholder="Nhập giá bán" min="1"required
                        name="product_price">
                    <span id="pro_price_error" class="text-danger"></span>
                </div>
                <div class="form-group">
                    <label for="description">Mô tả</label>
                    <textarea name="product_desc" class="form-control" id="product_desc" rows="3" placeholder="Mô tả sản phẩm">{!! isset($product) ? $product->description : '' !!}</textarea>
                    <span id="pro_desc_error" class="text-danger"></span>
                </div>
                <div class="form-group">
                    <label for="status">Trạng thái</label>
                    <select name="is_active" class="form-control" id="is_active">
                        <option selected value="1" {{ isset($product) && $product->is_active == 1 ? 'selected' : '' }}>
                            Đang bán
                        </option>
                        <option value="0" {{ isset($product) && $product->is_active == 0 ? 'selected' : '' }}>Hết hàng
                        </option>
                    </select>

                </div>
                <button type="button" id="create_edit_product"
                    data-product-id="{{ isset($product) ? $product->product_id : '' }}" class="btn btn-primary">Lưu</button>
                <a href="{{ route('product.index') }}"><button type="button" class="btn btn-secondary">Hủy</button></a>
            </div>
            <div class="col-md-5">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Sản phẩm</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Chi tiết sản phẩm</li>
                    </ol>
                </nav>
                <div class="form-group">
                    <label for="imageUpload">Hình ảnh</label>
                    <div class="custom-file">
                        <input accept="image/png, image/jpeg, image/jpg" type="file" class="custom-file-input" id="imageUpload">
                        <label class="custom-file-label" for="imageUpload">Chọn file</label>
                    </div>
                    <button class="btn btn-danger mt-2 remove_file">Xóa file</button>
                </div>
                <img width="250px;" src="{{ isset($product) ? asset($product->product_image) : '' }}" alt="">
                <span id="pro_img_error" class="text-danger"></span>
            </div>
        </div>
    </div>
@endsection

@push('head')
    <script src="{{ asset('js/handle_products.js') }}"></script>
    <script>
        let create_product_url = "{{ route('product.create') }}";
        let update_product_url = "{{ route('product.update', ['id' => ':id']) }}";
    </script>
    <script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            CKEDITOR.replace('product_desc');
        });
    </script>
@endpush
