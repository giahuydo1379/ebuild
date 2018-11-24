$(document).ready(function() {
    $('textarea.value').ckeditor();

    $('.add-logo').on('click', function () {
        var field = $('#link_logo').val();
        var value = $('#image_url_logo').val();
        if (field=='' || value=='') {
            $('.logo.error').show();
            return false;
        }
        var now = $.now();
        $('#list.sale-2b2_logo').prepend('<span><img src="'+value+'"></span><input type="hidden" value="'+value+'" name="logos['+now+'][name]" />' +
            '<input type="hidden" value="'+field+'" name="logos['+now+'][link]" />');

        $('#link_logo').val('');
        $('#image_url_logo').val('');
        $('.preview-banner-logo').attr('src', '/html/assets/images/img_upload.png');
    });

    $('.sale-b2b-logo .update-action').on('click', function (e) {
        e.preventDefault();

        $(this).hide();
        $(".sale-b2b-logo .box-display").slideUp();
        $(".sale-b2b-logo .box-update").slideDown();

        return false;
    });
    $('.sale-b2b-logo .add-action').on('click', function (e) {
        e.preventDefault();

        $(this).parent().slideUp();
        $(".sale-b2b-logo .box-update").slideDown();

        return false;
    });
    $(".sale-b2b-logo .cancel").on('click', function (e) {
        e.preventDefault();

        $(".sale-b2b-logo .box-display").slideDown();
        $('.sale-b2b-logo .add-action').parent().slideDown();
        $('.sale-b2b-logo .update-action').show();
        $(".sale-b2b-logo .box-update").slideUp();

        return false;
    });
    $('#form_sale_b2b_logo').validate({
        ignore: ".ignore",
        rules: {
            // 'description[value]': "required"
        },
        messages: {
            // 'description[value]': "Vui lòng nhập nội dung mô tả"
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
    //------------- start section sale-b2b_banner ---------------

    //------------- start section sale-b2b_process_buy ---------------

    $('.sale-b2b-banner .update-action').on('click', function (e) {
        e.preventDefault();

        $(this).hide();
        $(".sale-b2b-banner .banner-display").slideUp();
        $(".sale-b2b-banner .banner-update").slideDown();

        return false;
    });
    $('.sale-b2b-banner .add-action').on('click', function (e) {
        e.preventDefault();

        $(this).parent().slideUp();
        $(".sale-b2b-banner .banner-update").slideDown();

        return false;
    });
    $(".sale-b2b-banner .cancel").on('click', function (e) {
        e.preventDefault();

        $(".sale-b2b-banner .banner-display").slideDown();
        $('.sale-b2b-banner .add-action').parent().slideDown();
        $('.sale-b2b-banner .update-action').show();
        $(".sale-b2b-banner .banner-update").slideUp();

        return false;
    });
    $('#form_sale_b2b_banner').validate({
        ignore: ".ignore",
        rules: {
            'value': "required",
            'field': "required",
        },
        messages: {
            'value': "Vui lòng chọn ảnh",
            'field': "Vui lòng nhập liên kết",
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

    //---------------end section-----------------


    $('.sale-b2b-info .update-action').on('click', function (e) {
        e.preventDefault();

        $(this).hide();
        $(".sale-b2b-info .box-display").slideUp();
        $(".sale-b2b-info .box-update").slideDown();

        return false;
    });
    $('.sale-b2b-info .add-action').on('click', function (e) {
        e.preventDefault();

        $(this).parent().slideUp();
        $(".sale-b2b-info .box-update").slideDown();

        return false;
    });
    $(".sale-b2b-info .cancel").on('click', function (e) {
        e.preventDefault();

        $(".sale-b2b-info .box-display").slideDown();
        $('.sale-b2b-info .add-action').parent().slideDown();
        $('.sale-b2b-info .update-action').show();
        $(".sale-b2b-info .box-update").slideUp();

        return false;
    });
    $('#form_sale_b2b_info_update').validate({
        ignore: ".ignore",
        rules: {
            value: "required",
        },
        messages: {
            value: "Vui lòng nhập thông tin mô tả",
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