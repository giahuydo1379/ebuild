$(document).ready(function() {
    $('textarea.value').ckeditor();
    //------------- start section shopping_guide_banner ---------------

    $('.shopping_guide_banner_update').on('click', function (e) {
        e.preventDefault();

        $(this).hide();
        $(".banner-display").slideUp();
        $(".banner-update").slideDown();

        return false;
    });
    $('.shopping_guide_banner_add').on('click', function (e) {
        e.preventDefault();

        $(this).slideUp();
        $(".banner-update").slideDown();

        return false;
    });
    $(".banner-update .cancel").on('click', function (e) {
        e.preventDefault();

        $('.banner-display').slideDown();
        $('.shopping_guide_banner_add').slideDown();
        $('.shopping_guide_banner_update').show();
        $(".banner-update").slideUp();

        return false;
    });

    $('#form_shopping_guide_banner_update').validate({
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

    $('.shopping-guide-2nd .update-action').on('click', function (e) {
        e.preventDefault();

        $(this).hide();
        $(".shopping-guide-2nd .box-display").slideUp();
        $(".shopping-guide-2nd .box-update").slideDown();

        return false;
    });
    $('.shopping-guide-2nd .add-action').on('click', function (e) {
        e.preventDefault();

        $(this).parent().slideUp();
        $(".shopping-guide-2nd .box-update").slideDown();

        return false;
    });
    $(".shopping-guide-2nd .cancel").on('click', function (e) {
        e.preventDefault();

        $(".shopping-guide-2nd .box-display").slideDown();
        $('.shopping-guide-2nd .add-action').parent().slideDown();
        $('.shopping-guide-2nd .update-action').show();
        $(".shopping-guide-2nd .box-update").slideUp();

        return false;
    });
    $('#form_shopping_guide_2nd_update').validate({
        ignore: ".ignore",
        rules: {
            'banner_value': "required",
            'field': "required",
            'description_value': "required",
        },
        messages: {
            'banner_value': "Vui lòng chọn ảnh",
            'field': "Vui lòng nhập liên kết",
            'description_value': "Vui lòng nhập nội dung",
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


