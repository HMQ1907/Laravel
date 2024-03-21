$(document).ready(function () {
    const urlParams = new URLSearchParams(window.location.search);
    const user_name_url = urlParams.get('user_name');
    let nameError = $("#nameError");
    let emailError = $("#emailError");
    let passwordError = $("#passwordError");
    let isCreateMode = true;

    $('#user_name').val(user_name_url);

    $('#search-user').click();

    $(document).on('click', '.page-link', function(e) {
        e.preventDefault();
    
        let nextPageUrl = $(this).attr("href");
        let params = new URLSearchParams(nextPageUrl.split("?")[1]); 
    
        let currentParams = new URLSearchParams(window.location.search);
        params.forEach(function(value, key) {
            currentParams.set(key, value);
        });
    
        let newUrl = window.location.pathname + "?" + currentParams.toString();
    
        history.pushState({}, '', newUrl);
    
        $.ajax({
            url: nextPageUrl,
            method: "GET",
            success: function(response) {
                if (response.html) {
                    $("#table-user-info").html(response.html);
                    $("#pagination-links").html(response.pagination_links);
                } else {
                    $("#user-table-body").html('<tr><td colspan="6">Không có dữ liệu</td></tr>');
                    $("#pagination-links").html('');
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });

    $("#user_name, #user_email, #user_group_role, #user_is_active").keypress(function(event) {
        if (event.which == 13) { 
            event.preventDefault(); 
            $("#search-user").click();
        }
    });

    $("#search-user").click(function (e) {
        e.preventDefault();
        searchUsers();
    });

    $("#delete-search-user").click(function (e) {
        e.preventDefault();

        $("#user_name").val("");
        $("#user_email").val("");
        $("#user_group_role").val("");
        $("#user_is_active").val("");
        searchUsers();
    })

    function searchUsers(){
        let user_name = $("#user_name").val();
        let user_email = $("#user_email").val();
        let user_group_role = $("#user_group_role").val();
        let user_is_active = $("#user_is_active").val();

        let urlWithParams = `${search_user_url}?user_name=${user_name}&user_email=${user_email}&user_group_role=${user_group_role}&user_is_active=${user_is_active}`;
        let newUrl = window.location.pathname + '?' + urlWithParams.split('?')[1];
        history.pushState(null, null, newUrl);
        $.ajax({
            url: urlWithParams, 
            method: "GET",
            success: function (response) {
                if (response.html) {
                    $("#table-user-info").html(response.html);
                if(response.pagination_links){
                    $("#pagination-links").html(response.pagination_links);
                }
                } else {
                    $("#user-table-body").html('<tr><td colspan="6">Không có dữ liệu</td></tr>');
                    $(".pagination").html('');
                    $(".total_user").html('');
                }
            },
        });
    }
    
    $("#addUserBtn").click(function () {
        isCreateMode = true;

        nameError.text('');
        emailError.text('');
        passwordError.text('');

        $("#create_edit_user").removeAttr("data-user-id");
        $("#title_form").text("Thêm mới User");
        $("#userForm")[0].reset();
        $("#create_edit_user").text("Tạo User");
        $("#addEditUserModal").modal("show");

    });

    $(document).on("click", ".edit_user", function (e) {
        isCreateMode = false;
        e.preventDefault();
        nameError.text('');
        emailError.text('');
        passwordError.text('');
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

        nameError.text('');
        emailError.text('');
        passwordError.text('');

        let name = $("#name").val().trim();
        let email = $("#email").val().trim();
        let password = $("#password").val().trim();
        let confirm_password = $("#confirm_password").val().trim();;
        let user_group_role = $("#group_user").val();
        let is_active = $("#is_active").is(":checked") ? 1 : 0;
        

        if (!name) {
            nameError.text('Vui lòng nhập tên người dùng.');
        }

        if (!email) {
            emailError.text('Vui lòng nhập email người dùng.');
        }

        if (email && !validateEmail(email)) {
            emailError.text('Email không đúng định dạng.');
        }

        if (isCreateMode && !password) {
            passwordError.text('Vui lòng nhập mật khẩu người dùng.');
        }
        
        if (isCreateMode && name && name.length < 5) {
            $("#nameError").text('Tên người dùng phải có ít nhất 5 ký tự.');
        }
        
        if ((!isCreateMode && password && password.length < 5) || (isCreateMode && password && password.length < 5)) {
            passwordError.text('Mật khẩu người dùng phải có ít nhất 5 ký tự.');
        }

        if ((!isCreateMode && password && !validatePassword(password)) || (isCreateMode && password &&  !validatePassword(password))) {
            passwordError.text('Mật khẩu không bảo mật.');
        }

        if (password !== confirm_password) {
            passwordError.text('Mật khẩu và xác nhận mật khẩu không chính xác.');
        }

        if ( nameError.text() != '' || emailError.text() != '' || passwordError.text() != '') {
            return false;
        }

        if (isCreateMode) {
            $.ajax({
                type: "POST",
                url: create_user_url,
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
                    emailError.text(response.responseJSON.errors.email);
                }
            });
        } else {
            let userID = $(this).attr('data-user-id');
            $.ajax({
                type: "PUT",
                url: edit_user_url.replace(":id", userID),
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
                    if(response.responseJSON.name_error){
                        nameError.text(response.responseJSON.name_error);
                    }if(response.responseJSON.email_error){
                        emailError.text(response.responseJSON.email_error);
                    }
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
