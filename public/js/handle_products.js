$(document).ready(function () {

    let pro_name_error = $("#pro_name_error");
    let pro_price_error = $("#pro_price_error");
    let pro_desc_error = $("#pro_desc_error");
    let pro_img_error = $("#pro_img_error");

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
                    $("#table-product-info").html(response.html);
                    $("#pagination-links").html(response.pagination_links);
                } else {
                    $("#product-table-body").html(
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

    $("#product_name, #product_status, #min_price, #max_price").keypress(function(event) {
        if (event.which == 13) { 
            event.preventDefault(); 
            $("#search-product").click();
        }
    });

    function searchProducts() {
        let product_name = $("#product_name").val();
        let product_status = $("#product_status").val();
        let min_price = $("#min_price").val();
        let max_price = $("#max_price").val();
    
        let urlWithParams = `${search_product_url}?product_name=${product_name}&product_status=${product_status}&min_price=${min_price}&max_price=${max_price}`;
        let newUrl = window.location.pathname + '?' + urlWithParams.split('?')[1];
        history.pushState(null, null, newUrl);
        $.ajax({
            url: urlWithParams,
            method: "GET",
            success: function(response) {
                if (response.html) {
                    $("#table-product-info").html(response.html);
                    if (response.pagination_links) {
                        $("#pagination-links").html(response.pagination_links);
                    }
                } else {
                    $("#product-table-body").html('<tr><td colspan="6">Không có dữ liệu</td></tr>');
                    $(".pagination").html('');
                    $(".total_product").html('');
                }
            },
        });
    }
    
    $(document).on("click", "#search-product", function(e) {
        e.preventDefault();
        searchProducts();
    });
    
    $(document).on("click", "#delete-search", function(e) {
        e.preventDefault();
    
        $("#product_name").val('');
        $("#product_status").val('');
        $("#min_price").val('');
        $("#max_price").val('');
    
        searchProducts();
    });

    $(document).on("click","#create_edit_product",function(e) {

        pro_name_error.text('');
        pro_price_error.text('');
        pro_desc_error.text('');
        pro_img_error.text('');

        e.preventDefault();
        let product_id = $(this).attr("data-product-id");
        let product_name = $('#product_name').val();
        let product_desc = CKEDITOR.instances['product_desc'].getData();
        let product_price = $('#product_price').val();
        let is_active = $('#is_active').val();
        let image = $('#imageUpload').prop('files')[0];

        if (!product_name) {
            pro_name_error.text('Vui lòng nhập tên sản phẩm');
        }

        if (!product_price) {
            pro_price_error.text('Vui lòng nhập giá bán.');
        }

        if (!product_desc) {
            pro_desc_error.text('Vui lòng nhập mô tả sản phẩm.');
        }

        if (!image && !product_id) {
            pro_img_error.text('Vui lòng chọn ảnh sản phẩm.');
        }

        if (product_name && product_name.length < 5 ) {
            pro_name_error.text('Tên sản phẩm phải lớn hơn 5 kí tự.');
        }

        if (product_price && product_price < 0) {
            pro_price_error.text('Giá sản phẩm không được nhỏ hơn không.');
        }

        if (product_price && !isNumeric(product_price)) {
            pro_price_error.text('Gía sản phẩm phải là số.');
        }

        if (pro_name_error.text() != '' || pro_price_error.text() != '' || pro_desc_error.text() != '' || pro_img_error.text() != '') {
            return false;
        }

        let formData = new FormData();

        formData.append('product_name', product_name);
        formData.append('product_desc', product_desc);
        formData.append('product_price', product_price);
        formData.append('is_active', is_active);
        formData.append('image', image);

        if (product_id && product_id != 0 ){
            $.ajax({
                url: update_product_url.replace(":id", product_id), 
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
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
                        text: response.responseJSON.error,
                    });
                }
            });
        }else{
            $.ajax({
                url: create_product_url, 
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
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
                        text: response.responseJSON.message,
                    });
                }
            });
        }
    })

    $(document).on('mouseover','.product_name',function(){
        let imageSrc = $(this).data('image-src');
        if (imageSrc) {
            $(this).find('.product_img').html('<img width="120px;" src="' + imageSrc + '" alt="Product Image">');
            $(this).find('.product_img').removeClass('d-none');
        }
    })

    $(document).on('mouseout','.product_name',function(){
        $(this).find('.product_img').html('');
        $(this).find('.product_img').addClass('d-none');
    })

    $('.remove_file').click(function() {
        $('img').attr('src', '');
        $('#imageUpload').val('');
        $('#imageUpload').next('.custom-file-label').text('');
    });

    $('#imageUpload').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName);
    });

    $(document).on('click','.delete-product', function(e) {
        e.preventDefault();
        let product_id = $(this).data('product-id');
        var product_name = $(this).closest('tr').find('.product_name').text();
        Swal.fire({
            title: "Xác nhận",
            text: `Bạn có muốn xóa sản phẩm ${product_name} không`,
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Đồng ý",
            cancelButtonText: "Hủy bỏ"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "DELETE",
                    url: delete_product_url.replace(":id", product_id),
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
                            text: "Đã xảy ra lỗi khi xóa sản phẩm."
                        });
                    }
                });
            }
        });
    })

    function isNumeric(input) {
        return !isNaN(input) && !isNaN(parseFloat(input));
    }
    
    
});
