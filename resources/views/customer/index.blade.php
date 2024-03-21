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
                <button id="addCustomerBtn" class="btn btn-primary">Thêm mới</button>
            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text mr-1" id="basic-addon1"><i class="fa fa-search"
                            aria-hidden="true"></i></span>
                </div>
                <button type="button" id="search-customer" class="btn btn-success">Tìm kiếm</button>
            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text mr-1" id="basic-addon1"><i class="fa fa-times" aria-hidden="true"></i>
                    </span>
                </div>
                <button id="delete-search-customer" class="btn btn-danger">Xóa tìm</button>
            </div>
        </div>
        <div class="d-flex flex-row justify-content-start">
            <div style="margin-right: 200px;" class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text mr-1" id="basic-addon1"><i class="fa fa-download"
                            aria-hidden="true"></i></span>
                </div>
                <button id="exportCus" class="btn btn-warning">Export excel</button>
            </div>
        </div>
        <form id="importCusForm" enctype="multipart/form-data">
            @csrf
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text mr-1" id="basic-addon1"><i class="fa fa-upload" aria-hidden="true"></i></span>
                </div>
                <div class="custom-file">
                    <input accept=".xls,.xlsx," type="file" class="" id="excelFile" name="excelFile" aria-describedby="inputGroupFileAddon01">
                    <label style="width: 270px;" class="custom-file-label" for="excelFile">Chọn file Excel</label>
                </div>
                <button style="margin-right:690px;" id="importBtn" type="button" class="btn btn-info">Import excel</button>
            </div>
            
        </form>

        {{-- Start Table --}}
        @include('customer.table')
    </div>

    <div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="title_form"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- Start Form --}}
                    <form id="cusForm">
                        @csrf
                        <div class="form-group">
                            <label for="name">Tên</label>
                            <input type="text" id="name" name="name" class="form-control"
                                aria-describedby="emailHelp" placeholder="Customer name">
                            <span id="customer_name_error" class="text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label for="Email">Email</label>
                            <input type="text" name="email" id="email" class="form-control"
                                aria-describedby="emailHelp" placeholder="Customer email">
                            <span id="cus_email_error" class="text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label for="tel-num">Điện thoại</label>
                            <input name="tel-num" type="text" class="form-control" id="tel-num"
                                placeholder="Phone number">
                            <span id="cus_phone_error" class="text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label for="address">Địa chỉ</label>
                            <input type="text" class="form-control" id="address" name="address"
                                placeholder="Customer address">
                            <span id="cus_address_error" class="text-danger"></span>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4">Trạng thái</div>
                            <div class="col-sm-10">
                                <div class="form-check">
                                    <input name="is_active" checked class="form-check-input" type="checkbox"
                                        id="is_active">
                                    <label class="form-check-label" for="gridCheck1">
                                        Hoạt động
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-danger" type="button" class="close" data-dismiss="modal"
                                aria-label="Close">
                                Hủy
                            </button>
                            <button id="create_customer" type="button" class="btn btn-primary">Thêm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('head')
    <script src="{{ asset('js/handle_customers.js') }}"></script>
    <script>
        let search_cus_url = "{{ route('customer.index') }}";
        let create_cus_url = "{{ route('customer.create') }}";
        let update_cus_url = "{{ route('customer.update', ['id' => ':id']) }}";
        let export_cus_url = "{{ route('customer.export') }}";
        let import_cus_url = "{{ route('customer.import') }}";
        let delete_cus_url = "{{ route('customer.delete', ['id' => ':id']) }}";
    </script>
@endpush
