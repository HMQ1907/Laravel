$(document).ready(function () {

    $(document).on("click", ".page-link", function (e) {
        e.preventDefault();
        let nextPageUrl = $(this).attr("href");
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

    $("#search-product").click(function (e) {
        e.preventDefault(); 
        let product_name = $("#product_name").val();
        let product_status = $("#product_status").val();
        let min_price = $("#min_price").val();
        let max_price = $("#max_price").val();

        let urlWithParams = `${search_url}?product_name=${product_name}&product_status=${product_status}&min_price=${min_price}&max_price=${max_price}`;
        let newUrl = window.location.pathname + '?' + urlWithParams.split('?')[1];
        history.pushState(null, null, newUrl);
        $.ajax({
            url: urlWithParams, 
            method: "GET",
            success: function (response) {
                if (response.html) {
                    $("#table-product-info").html(response.html);
                if(response.pagination_links){
                    $("#pagination-links").html(response.pagination_links);
                }
                } else {
                    $("#product-table-body").html('<tr><td colspan="6">Không có dữ liệu</td></tr>');
                    $(".pagination").html('');
                    $(".total_user").html('');
                }
            },
        });
       
    });

    $(document).on("click","#create_edit_product",function(e) {

        e.preventDefault();
        let product_id = $(this).attr("data-product-id");
        let product_name = $('#product_name').val();
        let product_desc = CKEDITOR.instances['product_desc'].getData();
        let product_price = $('#product_price').val();
        let is_active = $('#is_active').val();
        let image = $('#imageUpload').prop('files')[0];
        let formData = new FormData();


        formData.append('product_name', product_name);
        formData.append('product_desc', product_desc);
        formData.append('product_price', product_price);
        formData.append('is_active', is_active);
        formData.append('image', image);

        if (product_id && product_id != 0 ){
            $.ajax({
                url: update_url.replace(":id", product_id), 
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
                url: create_url, 
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

    $('.product_name').mouseover(function() {
        var imageSrc = $(this).data('image-src');
        if (imageSrc) {
            $(this).find('.product_img').html('<img width="120px;" src="' + imageSrc + '" alt="Product Image">');
            $(this).find('.product_img').removeClass('d-none');
        }
    });

    $('.product_name').mouseout(function() {
        $(this).find('.product_img').html('');
        $(this).find('.product_img').addClass('d-none');
    });

    $('.remove_file').click(function() {
        $('img').attr('src', '');
        $('#imageUpload').val('');
        $('#imageUpload').next('.custom-file-label').text('');
    });

    $('#imageUpload').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName);
    });

});
