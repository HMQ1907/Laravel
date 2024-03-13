@extends('layout.app')
@section('content')
    <div class="container">
        <h3 class="text-left">Users</h3>
        <div style="gap: 5px;" class="info_user d-flex flex-row">
            <div class="input-group mb-3">
                <input id="user_name" type="text" class="form-control" name="name" placeholder="Username"
                    aria-label="Username" aria-describedby="basic-addon1">
            </div>
            <div class="input-group mb-3">
                <input id="user_email" type="text" class="form-control" name="email" placeholder="email"
                    aria-label="email" aria-describedby="basic-addon1">
            </div>

            <div class="input-group mb-3">
                <select id="user_group_role" name="user_group_role" class="form-control" id="group_role">
                    <option value="">Nhóm</option>
                    <option value="admin">Admin</option>
                    <option value="editor">Editor</option>
                    <option value="reviewer">Reviewer</option>
                </select>
            </div>

            <div class="input-group mb-3">
                <select id="user_is_active" name="is_active" class="form-control">
                    <option value="">Trạng thái</option>
                    <option value="1">Đang hoạt động</option>
                    <option value="0">Tạm khóa</option>
                </select>
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
    {{-- Table info users --}}
    @include('users.table')
    </div>
    <!-- Modal -->
    <div class="modal fade" id="addEditUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                    <form id="userForm">
                        @csrf
                        <div class="form-group">
                            <label for="name">Tên</label>
                            <input type="text" id="name" name="name" class="form-control"
                                aria-describedby="emailHelp" placeholder="Enter Name">
                        </div>
                        <div class="form-group">
                            <label for="Email">Email</label>
                            <input type="text" name="email" id="email" class="form-control"
                                aria-describedby="emailHelp" placeholder="Enter Email">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input name="password" type="password" class="form-control" id="password"
                                placeholder="Password">
                        </div>
                        <div class="form-group">
                            <label for="confirm_pasword">Xác nhận</label>
                            <input type="password" class="form-control" id="confirm_password" placeholder="Password">
                        </div>
                        <div class="form-group">
                            <label for="group_user">Nhóm</label>
                            <select name="group_user" class="form-control" id="group_user">
                                <option value="admin">Admin</option>
                                <option value="editor">Editor</option>
                                <option value="reviewer">Reviewer</option>
                            </select>
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
                        <div id="alert_error" class="text-danger" role="alert">
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-danger" type="button" class="close" data-dismiss="modal"
                                aria-label="Close">
                                Hủy
                            </button>
                            <button id="create_edit_user" type="button" class="btn btn-primary"></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('head')
    <script src="{{ asset('js/handle_users.js') }}"></script>
    <script src="
            https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.all.min.js
            "></script>
    <link href="
    https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.min.css" rel="stylesheet">
    <script>
        let search_url = "{{ route('user.search') }}";
        let create_url = "{{ route('user.create') }}";
        let detail_user_url = "{{ route('user.detail', ['id' => ':id']) }}";
        let edit_user_url = "{{ route('user.update', ['id' => ':id']) }}";
        let delete_user_url = "{{ route('user.delete', ['id' => ':id']) }}";
        let block_user_url = "{{ route('user.block', ['id' => ':id']) }}";
    </script>
@endpush
