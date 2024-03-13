$(document).ready(function () {
    $("#search-user").click(function (e) {
        e.preventDefault();

        let user_name = $("#user_name").val();
        let user_email = $("#user_email").val();
        let user_group_role = $("#user_group_role").val();
        let user_is_active = $("#user_is_active").val();

        $.ajax({
            url: search_url,
            method: "POST",
            data: {
                user_name: user_name,
                user_email: user_email,
                user_group_role: user_group_role,
                user_is_active: user_is_active,
            },
            success: function (response) {
                if(response.users.data.length > 0) {
                $("#user-table-body").empty();
                $("#paginate_all").empty();
                $.each(response.users.data, function (index, user) {
                    $("#user-table-body").append(
                        "<tr>" +
                            '<th scope="row">' +
                            (index + 1) +
                            "</th>" +
                            "<td class='user_name'>" +
                            user.name +
                            "</td>" +
                            "<td>" +
                            user.email +
                            "</td>" +
                            "<td>" +
                            user.group_role +
                            "</td>" +
                            "<td>" +
                            (user.is_active == 1
                                ? '<span data-active="1" class="text-success"> Đang hoạt động</span>'
                                : '<span data-active="0" class="text-danger"> Tạm khóa</span>') +
                            "</td>" +
                            '<td style="gap: 5px;" class="d-flex flex-row">' +
                            '<i id="edit_user_' + user.id + '" style="color: blue;cursor:pointer" class="fa fa-pencil edit_user" aria-hidden="true"></i>' +
                            '<i id="remove_user_'+user.id + '"style="color: red;cursor:pointer"  class="fa fa-trash remove_user" aria-hidden="true"></i>' +
                            '<i id="block_user_'+user.id + '" style="color: black;cursor:pointer" class="fa fa-user-times block_user" aria-hidden="true"></i>' +
                            "</td>" +
                            "</tr>"
                    );
                });
                $("#pagination-links").html(response.pagination);
                }else{
                    $("#user-table-body").text('Không có dữ liệu');
                }
            },
        });
    });
    let isCreateMode = true;

    $("#addUserBtn").click(function () {
        isCreateMode = true;
        $("#create_edit_user").removeAttr("data-user-id");
        $("#title_form").text("Thêm mới User");
        $("#userForm")[0].reset();
        $("#create_edit_user").text("Tạo User");
        $("#alert_error").empty();
        $("#addEditUserModal").modal("show");
    });

    $(document).on("click", ".edit_user", function (e) {
        isCreateMode = false;
        e.preventDefault();
        $("#title_form").text("Chỉnh sửa người dùng");
        $("#create_edit_user").text("Chỉnh sửa ");
        $("#addEditUserModal").modal("show");
        $("#alert_error").empty();
        var userId = $(this).attr("id").split("_")[2];
        var detail_url = detail_user_url.replace(":id", userId);
        $("#create_edit_user").attr('data-user-id', userId);
        $.ajax({
            url: detail_url,
            method: "GET",
            success: function (response) {
                $("#name").val(response.name);
                $("#email").val(response.email);
                $("#password").val(response.password);
                $("#group_user").val(response.group_role);
                if (response.is_active == 0) {
                    $("#is_active").prop("checked", false);
                } else {
                    $("#is_active").prop("checked", true);
                }
            },
        });
    });

    $(document).on("click", ".remove_user", function(e) {

        e.preventDefault();
        let userId = $(this).attr("id").split("_")[2];
        var user_name = $(this).closest('tr').find('.user_name').text();
        Swal.fire({
            title: "Xác nhận",
            text: `Bạn có muốn xóa ${user_name} không`,
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Đồng ý",
            cancelButtonText: "Hủy bỏ"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "DELETE",
                    url: delete_user_url.replace(":id", userId),
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công',
                            text: response.message,
                            willClose:() =>{
                                window.location.reload();
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: "error",
                            title: "Lỗi",
                            text: "Đã xảy ra lỗi khi xóa người dùng."
                        });
                    }
                });
            }
        });
    });

    $("#create_edit_user").click(function () {
        let name = $("#name").val();
        let email = $("#email").val();
        let password = $("#password").val();
        let confirm_password = $("#confirm_password").val();
        let user_group_role = $("#group_user").val();
        let is_active = $("#is_active").is(":checked") ? 1 : 0;
        let error_messages = [];

        if (password !== confirm_password) {
            error_messages.push(
                "Mật khẩu và xác nhận mật khẩu không chính xác."
            );
        }
        console.log(isCreateMode);
        if (!name) {
            error_messages.push("Vui lòng nhập tên người dùng.");
        }
        if (!email) {
            error_messages.push("Vui lòng nhập email người dùng.");
        }
        if (email && !validateEmail(email)) {
            error_messages.push("Email không đúng định dạng");
        }
        if (isCreateMode && !password) {
            error_messages.push("Vui lòng nhập mật khẩu người dùng.");
        }
        if (isCreateMode && name && name.length < 5) {
            error_messages.push("Tên người dùng phải có ít nhất 5 ký tự.");
        }
        if ((!isCreateMode && password && password.length < 5) || (isCreateMode && password && password.length < 5)) {
            error_messages.push("Mật khẩu người dùng phải có ít nhất 5 ký tự.");
        }
        if ((!isCreateMode && password && !validatePassword(password)) || (isCreateMode && password &&  !validatePassword(password))) {
            error_messages.push("Mật khẩu không bảo mật");
        }

        if (error_messages.length > 0) {
            $("#alert_error").html(error_messages.join("<br>"));
            return false;
        }
        if (isCreateMode) {
            $.ajax({
                type: "POST",
                url: create_url,
                data: {
                    name: name,
                    email: email,
                    password: password,
                    user_group_role: user_group_role,
                    is_active: is_active,
                },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công',
                        text: response.message,
                        willClose:() =>{
                            window.location.reload();
                        }
                    });
                 },error: function (response) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Thất bại',
                        text: response.responseJSON.message,
                    });
                }
            });
        } else {
            let userID = $(this).attr('data-user-id');
            let edit_url = edit_user_url.replace(":id", userID);
            $.ajax({
                type: "PUT",
                url: edit_url,
                data: {
                    id: userID,
                    name: name,
                    email: email,
                    password: password,
                    user_group_role: user_group_role,
                    is_active: is_active,
                },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công',
                        text: response.message,
                        willClose:() =>{
                            window.location.reload();
                        }
                    });
                 },error: function (response) {
                    console.log(response);
                    Swal.fire({
                        icon: 'error',
                        title: 'Thất bại',
                        text: response.responseJSON.error,
                    });
                },
            });
        }
    });

    $(document).on("click", ".block_user", function(e) {
        let userId = $(this).attr("id").split("_")[2];
        var user_name = $(this).closest('tr').find('.user_name').text();
        var data_active = $('#remove_user_' + userId).closest('tr').find('span').attr('data-active');
        let noti = data_active == 1 ? "Khóa" : "Bỏ khóa" ;
        Swal.fire({
            title: "Xác nhận",
            text: `Bạn có muốn ${noti} ${user_name} không`,
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Đồng ý",
            cancelButtonText: "Hủy bỏ"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "patch",
                    url: block_user_url.replace(":id", userId),
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công',
                            text: response.message,
                            willClose:() =>{
                                window.location.reload();
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: "error",
                            title: "Lỗi",
                            text: "Đã xảy ra lỗi khi xóa người dùng."
                        });
                    }
                });
            }
        });
    });

    function validatePassword(password) {
        let passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
        return passwordRegex.test(password);
    }
    function validateEmail(email) {
        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
});
