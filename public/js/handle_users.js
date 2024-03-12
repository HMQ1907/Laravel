$(document).ready(function () {
    $('#search-user').click(function (e) {
        e.preventDefault();
        
        let user_name = $('#user_name').val();
        let user_email = $('#user_email').val();
        let user_group_role = $('#user_group_role').val();
        let user_is_active = $('#user_is_active').val();
        
        $.ajax({
            url: search_url,
            method: 'POST',
            data: {
                user_name: user_name,
                user_email: user_email,
                user_group_role: user_group_role,
                user_is_active: user_is_active
            },
            success: function (response) {
                $('#user-table-body').empty();
                $.each(response.users, function(index, user) {
                    $('#user-table-body').append(
                        '<tr>' +
                            '<th scope="row">' + (index + 1) + '</th>' +
                            '<td>' + user.name + '</td>' +
                            '<td>' + user.email + '</td>' +
                            '<td>' + user.group_role + '</td>' +
                            '<td>' + (user.is_active == 1 ? '<span class="text-success"> Đang hoạt động</span>' : '<span class="text-danger"> Tạm khóa</span>') + '</td>' +
                            '<td style="gap: 5px;" class="d-flex flex-row">' +
                                '<i style="color: blue;cursor:pointer" class="fa fa-pencil edit_user" aria-hidden="true"></i>' +
                                '<i style="color: red;cursor:pointer"  class="fa fa-trash remove_user" aria-hidden="true"></i>' +
                                '<i style="color: black;cursor:pointer" class="fa fa-user-times block_user" aria-hidden="true"></i>' +
                            '</td>' +
                        '</tr>'
                    );
                });
            },
            
        });
    });

    $('#addUserBtn').click(function () {
        $('#title_form').text('Thêm người dùng');
        $('#addEditUserModal').modal('show');
    });

    $('#create_user').click(function () {
        let name = $('#name').val();
        let email = $('#email').val();
        let password = $('#password').val();
        let confirm_password = $('#confirm_password').val();
        let user_group_role = $('#group_user').val();
        let is_active = $('#is_active').val();
        let error_messages = [];
    
        if (password !== confirm_password) {
            error_messages.push('Mật khẩu và xác nhận mật khẩu không chính xác.');
        }
        if (!name) {
            error_messages.push('Vui lòng nhập tên người dùng.');
        }
        if (!email) {
            error_messages.push('Vui lòng nhập email người dùng.');        
        }
        if (email && !validateEmail(email)) {
            error_messages.push('Email không đúng định dạng');
        }
        if (!password) {
            error_messages.push('Vui lòng nhập mật khẩu người dùng.');
        }
        if (name && name.length < 5) {
            error_messages.push('Tên người dùng phải có ít nhất 5 ký tự.');
        }
        if (password && password.length < 5) {
            error_messages.push('Mật khẩu người dùng phải có ít nhất 5 ký tự.');
        }
        if (password && !validatePassword(password)) {
            error_messages.push('Mật khẩu không bảo mật');
        }
    
        if (error_messages.length > 0) {
            $('#alert_error').html(error_messages.join('<br>'));
            return false;
        }

        $.ajax({
            type: "POST",
            url: create_url,
            data: {
                name: name,
                email: email,
                password: password,
                user_group_role: user_group_role,
                is_active: is_active
            },
            success: function (response) {
                
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
