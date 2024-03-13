@extends('layout.app')
@section('content')
    <div class="container">
        <h3 class="text-left">Khách hàng</h3>
        <div style="gap: 5px;" class="info_user d-flex flex-row">
            <div class="input-group mb-3">
                <input id="customer_name" type="text" class="form-control" name="customer_name" placeholder="Nhâp họ tên"
                    aria-label="Username" aria-describedby="basic-addon1">
            </div>
            <div class="input-group mb-3">
                <input id="customer_email" type="text" class="form-control" name="customer_email"
                    placeholder="Nhập email" aria-label="email" aria-describedby="basic-addon1">
            </div>

            <div class="input-group mb-3">
                <select id="customer_status" name="customer_status" class="form-control" id="customer_status">
                    <option value="">Trạng thái</option>
                    <option value="1">Đang hoạt động</option>
                    <option value="0">Tạm khóa</option>
                </select>
            </div>
            <div class="input-group mb-3">
                <input id="customer_address" type="text" class="form-control" name="customer_address"
                    placeholder="Nhập địa chỉ" aria-label="email" aria-describedby="basic-addon1">
            </div>
        </div>
        <div style="gap: 15px;" class="info_user d-flex flex-row">
            <div style="margin-right: 645px" class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text mr-1" id="basic-addon1"><i class="fa fa-user-plus"
                            aria-hidden="true"></i></span>
                </div>
                <button id="addUserBtn" class="btn btn-primary"> Thêm mới </button>
            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text mr-1" id="basic-addon1"><i class="fa fa-search"
                            aria-hidden="true"></i></span>
                </div>
                <button type="button" id="search-user" class="btn btn-success">Tìm kiếm</button>
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
        @include('customer.table')
    </div>
@endsection
@push('head')
    <script src="{{ asset('js/handle_customers.js') }}"></script>
    <script src="
                https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.all.min.js
                "></script>
    <link href="
    https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.min.css" rel="stylesheet">
    <script>
        // let search_url = "{{ route('user.search') }}";
        // let create_url = "{{ route('user.create') }}";
        // let detail_user_url = "{{ route('user.detail', ['id' => ':id']) }}";
        // let edit_user_url = "{{ route('user.update', ['id' => ':id']) }}";
        // let delete_user_url = "{{ route('user.delete', ['id' => ':id']) }}";
        // let block_user_url = "{{ route('user.block', ['id' => ':id']) }}";
    </script>
@endpush
