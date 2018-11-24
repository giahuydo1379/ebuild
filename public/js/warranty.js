$(document).ready(function() {
    $('textarea.value').ckeditor();
    //------------- start section shopping_guide_banner ---------------

    $('.warranty_banner_update').on('click', function (e) {
        e.preventDefault();

        $(this).hide();
        $(".banner-display").slideUp();
        $(".banner-update").slideDown();

        return false;
    });
    $('.warranty_banner_add').on('click', function (e) {
        e.preventDefault();

        $(this).slideUp();
        $(".banner-update").slideDown();

        return false;
    });
    $(".banner-update .cancel").on('click', function (e) {
        e.preventDefault();

        $('.banner-display').slideDown();
        $('.warranty_banner_add').slideDown();
        $('.warranty_banner_update').show();
        $(".banner-update").slideUp();

        return false;
    });

    $('#form_warranty_banner_update').validate({
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

    $('.warranty-description .update-action').on('click', function (e) {
        e.preventDefault();

        $(this).hide();
        $(".warranty-description .box-display").slideUp();
        $(".warranty-description .box-update").slideDown();

        return false;
    });
    $('.warranty-description .add-action').on('click', function (e) {
        e.preventDefault();

        $(this).parent().slideUp();
        $(".warranty-description .box-update").slideDown();

        return false;
    });
    $(".warranty-description .cancel").on('click', function (e) {
        e.preventDefault();

        $(".warranty-description .box-display").slideDown();
        $('.warranty-description .add-action').parent().slideDown();
        $('.warranty-description .update-action').show();
        $(".warranty-description .box-update").slideUp();

        return false;
    });
    $('#form_warranty_description_update').validate({
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


