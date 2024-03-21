$(document).ready(function () {
    const urlParams = new URLSearchParams(window.location.search);
    const cus_name_url = urlParams.get("customer_name");
    
    let customer_name_error = $("#customer_name_error");
    let cus_email_error = $("#cus_email_error");
    let cus_phone_error = $("#cus_phone_error");
    let cus_address_error = $("#cus_address_error");

    $("#customer_name").val(cus_name_url);

    $("#search-customer").click();

    $(document).on("click", ".page-link", function (e) {
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
            success: function (response) {
                if (response.html) {
                    $("#table-cus-info").html(response.html);
                    $("#pagination-links").html(response.pagination_links);
                } else {
                    $("#cus-table-body").html(
                        '<tr><td colspan="6">Không có dữ liệu</td></tr>'
                    );
                    $("#pagination-links").html("");
                }
            },
            error: function (xhr, status, error) {
                console.error(error);
            },
        });
    });

    $("#customer_name, #customer_email, #customer_status, #customer_address").keypress(function (event) {
        if (event.which == 13) {
            event.preventDefault();
            $("#search-customer").click();
        }
    });

    $("#search-customer").click(function (e) {
        e.preventDefault();
        searchCustomers();
    });

    $("#delete-search-customer").click(function (e) {
        e.preventDefault();
        $("#customer_name").val('');
        $("#customer_email").val('');
        $("#customer_status").val('');
        $("#customer_address").val('');
        searchCustomers();
    });

    function searchCustomers(){
        let customer_name = $("#customer_name").val();
        let customer_email = $("#customer_email").val();
        let customer_status = $("#customer_status").val();
        let customer_address = $("#customer_address").val();

        let urlWithParams = `${search_cus_url}?customer_name=${customer_name}&customer_email=${customer_email}&customer_status=${customer_status}&customer_address=${customer_address}`;
        let newUrl =
            window.location.pathname + "?" + urlWithParams.split("?")[1];
        history.pushState(null, null, newUrl);
        $.ajax({
            url: urlWithParams,
            method: "GET",
            success: function (response) {
                if (response.html) {
                    $("#table-cus-info").html(response.html);
                    if (response.pagination_links) {
                        $("#pagination-links").html(response.pagination_links);
                    }
                } else {
                    $("#customer-table-body").html(
                        '<tr><td colspan="6">Không có dữ liệu</td></tr>'
                    );
                    $(".pagination").html("");
                    $(".total_customer").html('');

                }
            },
        });
    }

    $("#addCustomerBtn").click(function () {

        customer_name_error.text('');
        cus_email_error.text('');
        cus_phone_error.text('');
        cus_address_error.text('');

        $("#cusForm")[0].reset();
        $("#title_form").text("Thêm mới khách hàng");
        $("#alert_error").empty();
        $("#addCustomerModal").modal("show");
    });

    $("#create_customer").click(function () {

        customer_name_error.text('');
        cus_email_error.text('');
        cus_phone_error.text('');
        cus_address_error.text('');

        let name = $("#name").val().trim();
        let email = $("#email").val().trim();
        let tel_num = $("#tel-num").val().trim();
        let address = $("#address").val().trim();
        let is_active = $("#is_active").is(":checked") ? 1 : 0;

        if (!name) {
            customer_name_error.text('Vui lòng nhập tên khách hàng');
        }
        if (name && name.length < 5) {
            cus_name_error.text('Tên khách hàng phải có ít nhất 5 ký tự.');
        }
        if (!email) {
            cus_email_error.text('Vui lòng nhập email khách hàng');
        }
        if (!tel_num) {
            cus_phone_error.text('Vui lòng nhập số điện thoại khách hàng');
        }
        if (tel_num && !validatePhoneNumber(tel_num)) {
            cus_phone_error.text('Số điện thoại không đúng định dạng');
        }
        if (email && !validateEmail(email)) {
            cus_email_error.text('Email không đúng định dạng');
        }
        if (!address) {
            cus_address_error.text('Vui lòng nhập đia chỉ khách hàng');
        }

        if ( customer_name_error.text() != '' || cus_email_error.text() != '' || cus_phone_error.text() != '' || cus_address_error.text() != '' ) {
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
                    icon: "success",
                    title: "Thành công",
                    text: response.message,
                    willClose: () => {
                        window.location.reload();
                    },
                });
            },
            error: function (response) {
                cus_email_error.text(response.responseJSON.message)
            },
        });
    });

    $(document).on("click", ".edit-customer", function (e) {
        let row = $(this).closest("tr");
        if (row.hasClass("editing")) {
            $(this).addClass("fa-pencil");
            row.find(".update-customer-btn").hide();
            $(this).removeClass("fa-times-circle");
            row.find("td:not(:last-child)").each(function () {
                let fieldValue = $(this).find("input").val();
                $(this).text(fieldValue);
            });
            row.find(".delete-customer").show();
            row.removeClass("editing");
        } else {
            $(this).removeClass("fa-pencil");
            $(this).addClass("fa-times-circle");
            row.find("td:not(:last-child)").each(function () {
                let fieldName = $(this).attr("class");
                let fieldValue = $(this).text();
                $(this).html(
                    '<input type="text" class="form-control" name="' +
                        fieldName +
                        '" value="' +
                        fieldValue +
                        '">'
                );
            });
            row.find(".update-customer-btn").show();
            row.find(".delete-customer").hide();
            row.addClass("editing");
        }
    });

    $(document).on("click", ".update-customer-btn", function () {
        let row = $(this).closest("tr");
        let updatedData = {};

        row.find("td:not(:last-child) input").each(function () {
            let fieldName = $(this).attr("name");
            let fieldValue = $(this).val();
            updatedData[fieldName] = fieldValue;
        });
        let cus_id = $(this).attr("data-customer-id");
        updatedData["customer_id"] = cus_id;
        console.log(updatedData);
        $.ajax({
            type: "PUT",
            url: update_cus_url.replace(":id", cus_id),
            data: updatedData,
            success: function (response) {
                Swal.fire({
                    icon: "success",
                    title: "Thành công",
                    text: response.message,
                    willClose: () => {
                        window.location.reload();
                    },
                });
            },
            error: function (response) {
                Swal.fire({
                    icon: "error",
                    title: "Lỗi",
                    text: response.responseJSON.error,
                });
            },
        });
    });

    $(document).on("click", ".delete-customer", function(e){
        e.preventDefault();
        let cus_id = $(this).attr('data-customer-id');
        var cus_name = $(this).closest('tr').find('.customer_name').text();
        Swal.fire({
            title: "Xác nhận",
            text: `Bạn có muốn xóa khách hàng ${cus_name} không`,
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Đồng ý",
            cancelButtonText: "Hủy bỏ"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "DELETE",
                    url: delete_cus_url.replace(":id", cus_id),
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
                            text: "Đã xảy ra lỗi khi xóa khách hàng."
                        });
                    }
                });
            }
        });
    });

    $(document).on("click", "#exportCus", function () {

        let cus_name = $("#customer_name").val();
        let cus_email = $("#customer_email").val();
        let cus_status = $("#customer_status").val();
        let cus_address = $("#customer_address").val();

        let urlWithParams = `${export_cus_url}?customer_name=${cus_name}&customer_email=${cus_email}&customer_status=${cus_status}&customer_address=${cus_address}`;
        window.location = urlWithParams

    });
    
    $('#excelFile').change(function () {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName);
    });

    $("#importBtn").click(function(e) {
        e.preventDefault();
        let fileInput = document.getElementById('excelFile');
        let file = fileInput.files[0];
        
        if (!file) {
            alert('Chưa chọn file');
            return;
        }
    
        let reader = new FileReader();
        reader.onload = function(e) {    
            $.ajax({
                type: "POST",
                url: import_cus_url,
                data: new FormData($("#importCusForm")[0]),
                contentType: false,
                processData: false,
                success: function (response) {
                    Swal.fire({
                        icon: "success",
                        title: "Thành công",
                        text: response.success,
                        willClose: () => {
                            window.location.reload();
                        },
                    });
                },
                error: function (response) {
                    console.log(response);
                    Swal.fire({
                        icon: "error",
                        title: "Lỗi",
                        html: response.responseJSON.error.replace(/\n/g, "<br>"),
                    });
                },
            });
        };
    
        reader.readAsArrayBuffer(file); 
    });
    
    
});

function validateEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}
function validatePhoneNumber(phoneNumber) {
    return /^\d{10}$/.test(phoneNumber);
}
