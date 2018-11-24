$(document).ready(function() {
    $('textarea.value').ckeditor();
    //------------- start section shopping_guide_banner ---------------

    $('.delivery_banner_update').on('click', function (e) {
        e.preventDefault();

        $(this).hide();
        $(".banner-display").slideUp();
        $(".banner-update").slideDown();

        return false;
    });
    $('.delivery_banner_add').on('click', function (e) {
        e.preventDefault();

        $(this).slideUp();
        $(".banner-update").slideDown();

        return false;
    });
    $(".banner-update .cancel").on('click', function (e) {
        e.preventDefault();

        $('.banner-display').slideDown();
        $('.delivery_banner_add').slideDown();
        $('.delivery_banner_update').show();
        $(".banner-update").slideUp();

        return false;
    });

    $('#form_delivery_banner_update').validate({
        ignore: ".ignore",
        rules: {
            value: "required",
            field: "required",
        },
        messages: {
            value: "Vui lòng chọn ảnh",
            field: "Vui lòng nhập liên kết",
        },
        submitHandler: function(form) {
            // do other things for a valid form
            var data = $(form).serializeArray();
            $.post($(form).attr('action'), data).done(function(data){
                if(data.rs == 1)
                {
                    alert_success(data.msg, function () {
                        location.reload();
                    });
                } else {
                    alert_success(data.msg);
                }
            });

            return false;
        }
    });

    //------------ end section -----------------

    //------------- start section amortization_process_buy ---------------

    $('.delivery-description .update-action').on('click', function (e) {
        e.preventDefault();

        $(this).hide();
        $(".delivery-description .box-display").slideUp();
        $(".delivery-description .box-update").slideDown();

        return false;
    });
    $('.delivery-description .add-action').on('click', function (e) {
        e.preventDefault();

        $(this).parent().slideUp();
        $(".delivery-description .box-update").slideDown();

        return false;
    });
    $(".delivery-description .cancel").on('click', function (e) {
        e.preventDefault();

        $(".delivery-description .box-display").slideDown();
        $('.delivery-description .add-action').parent().slideDown();
        $('.delivery-description .update-action').show();
        $(".delivery-description .box-update").slideUp();

        return false;
    });
    $('#form_delivery_description_update').validate({
        ignore: ".ignore",
        rules: {
            'value': "required",
        },
        messages: {
            'value': "Vui lòng nhập thông tin mô tả",
        },
        submitHandler: function(form) {
            // do other things for a valid form
            var data = $(form).serialize();
            $.post($(form).attr('action'), data).done(function(data){
                if(data.rs == 1)
                {
                    alert_success(data.msg, function () {
                        location.reload();
                    });
                } else {
                    alert_success(data.msg);
                }
            });

            return false;
        }
    });

});


