$(document).ready(function (){

    const urlParams = new URLSearchParams(window.location.search);
    const cus_name_url = urlParams.get('customer_name');

    $('#customer_name').val(cus_name_url);

    $('#search-customer').click();

    $(document).on('click', '.page-link', function(e) {
        e.preventDefault();
    
        let nextPageUrl = $(this).attr('href');
    
        $.ajax({
            url: nextPageUrl,
            method: "GET",
            success: function(response) {
                if (response.html) {
                    $("#table-cus-info").html(response.html);
                    $("#pagination-links").html(response.pagination_links);
                } else {
                    $("#cus-table-body").html('<tr><td colspan="6">Không có dữ liệu</td></tr>');
                    $("#pagination-links").html('');
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });

    $("#customer_name, #customer_email, #customer_status, #customer_address").keypress(function(event) {
        if (event.which == 13) { 
            event.preventDefault(); 
            $("#search-customer").click();
        }
    });

    $("#search-customer").click(function (e) {
        e.preventDefault(); 
        let customer_name = $("#customer_name").val();
        let customer_email = $("#customer_email").val();
        let customer_status = $("#customer_status").val();
        let customer_address = $("#customer_address").val();

        let urlWithParams = `${search_cus_url}?customer_name=${customer_name}&customer_email=${customer_email}&customer_status=${customer_status}&customer_address=${customer_address}`;
        let newUrl = window.location.pathname + '?' + urlWithParams.split('?')[1];
        history.pushState(null, null, newUrl);
        $.ajax({
            url: urlWithParams, 
            method: "GET",
            success: function (response) {
                if (response.html) {
                    $("#table-cus-info").html(response.html);
                if(response.pagination_links){
                    $("#pagination-links").html(response.pagination_links);
                }
                } else {
                    $("#customer-table-body").html('<tr><td colspan="6">Không có dữ liệu</td></tr>');
                    $(".pagination").html('');
                }
            },
        });
       
    });

    $("#addCustomerBtn").click(function () {
        $("#title_form").text("Thêm mới khách hàng");
        $("#alert_error").empty();
        $("#addCustomerModal").modal("show");
    });

    $("#create_customer").click(function () {
        let name = $("#name").val();
        let email = $("#email").val();
        let tel_num = $("#tel-num").val();
        let address = $("#address").val();
        let is_active = $("#is_active").is(":checked") ? 1 : 0;
        let error_messages = [];

        if (!name) {
            error_messages.push("Vui lòng nhập tên khách hàng");
        }
        if (name && name.length < 5) {
            error_messages.push("Tên khách hàng phải có ít nhất 5 ký tự.");
        }
        if (!email) {
            error_messages.push("Vui lòng nhập email khách hàng");
        }
        if(!tel_num){
            error_messages.push(" Vui lòng nhập số điện thoại khách hàng");
        }
        if (tel_num && !validatePhoneNumber(tel_num)) {
            error_messages.push("Số điện thoại không đúng định dạng");
        }
        if (email && !validateEmail(email)) {
            error_messages.push("Email không đúng định dạng");
        }
        if (!address) {
            error_messages.push("Vui lòng nhập đia chỉ khách hàng");
        }

        if (error_messages.length > 0) {
            $("#alert_error").html(error_messages.join("<br>"));
            return false;
        }
            $.ajax({
                type: "POST",
                url: create_cus_url,
                data: {
                    name: name,
                    email: email,
                    tel_num: tel_num,
                    address: address,
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
    });

    $(document).on('click', '.edit-customer', function(e){
        let row = $(this).closest('tr');
        if (row.hasClass('editing')) {
            $(this).addClass('fa-pencil');
            row.find('.update-customer-btn').hide();
            $(this).removeClass('fa-times-circle');
            row.find('td:not(:last-child)').each(function() {
                let fieldValue = $(this).find('input').val();
                $(this).text(fieldValue);
            });
    
            row.removeClass('editing');
        } else {
            $(this).removeClass('fa-pencil');
            $(this).addClass('fa-times-circle');
            row.find('td:not(:last-child)').each(function() {
                let fieldName = $(this).attr('class');
                let fieldValue = $(this).text();
                $(this).html('<input type="text" class="form-control" name="' + fieldName + '" value="' + fieldValue + '">');
            });
            row.find('.update-customer-btn').show();
            row.addClass('editing');
        }
    });

    $(document).on('click', '.update-customer-btn', function() {
        let row = $(this).closest('tr');
        let updatedData = {};
        
        row.find('td:not(:last-child) input').each(function() {
            let fieldName = $(this).attr('name');
            let fieldValue = $(this).val();
            updatedData[fieldName] = fieldValue;
        });
        let cus_id = $(this).attr('data-customer-id');
        updatedData['customer_id'] = cus_id;
        console.log(updatedData);;
        $.ajax({
            type: 'PUT',
            url: update_cus_url.replace(":id", cus_id),
            data: updatedData,
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
            error: function(response) {
                Swal.fire({
                    icon: "error",
                    title: "Lỗi",
                    text: response.responseJSON.error
                });
            }
        });
    });
    
    
});
function validateEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}
function validatePhoneNumber(phoneNumber) {
    return /^\d{10}$/.test(phoneNumber);
}