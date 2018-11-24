$(document).ready(function() {
    $('textarea.value').ckeditor();
    $('.add-action-line').on('click', function () {
        add_line();
    });
    $('.amortization-line .update-action').on('click', function (e) {
        e.preventDefault();

        $(this).hide();
        $(".amortization-line .box-display").slideUp();
        $(".amortization-line .box-update").slideDown();

        return false;
    });
    $('.amortization-line .add-action').on('click', function (e) {
        e.preventDefault();

        $(this).parent().slideUp();
        $(".amortization-line .box-update").slideDown();

        return false;
    });
    $(".amortization-line .cancel").on('click', function (e) {
        e.preventDefault();

        $(".amortization-line .box-display").slideDown();
        $('.amortization-line .add-action').parent().slideDown();
        $('.amortization-line .update-action').show();
        $(".amortization-line .box-update").slideUp();

        return false;
    });
    $('#form_amortization_line').validate({
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

    $('.add-partner').on('click', function () {
        var field = $('#link_partner').val();
        var value = $('#image_url_partner').val();
        if (field=='' || value=='') {
            $('.partner.error').show();
            return false;
        }
        var now = $.now();
        $('#list.amortization_partner').prepend('<span><img src="'+value+'"></span><input type="hidden" value="'+value+'" name="partners['+now+'][name]" />' +
            '<input type="hidden" value="'+field+'" name="partners['+now+'][link]" />');

        $('#link_partner').val('');
        $('#image_url_partner').val('');
        $('.preview-banner-partner').attr('src', '/html/assets/images/img_upload.png');
    });
    $('.add-bank').on('click', function () {
        var field = $('#link_bank').val();
        var value = $('#image_url_bank').val();
        if (field=='' || value=='') {
            $('.bank.error').show();
            return false;
        }
        var now = $.now();
        $('#list.amortization_bank').prepend('<span><img src="'+value+'"></span><input type="hidden" value="'+value+'" name="banks['+now+'][name]" />' +
        '<input type="hidden" value="'+field+'" name="banks['+now+'][link]" />');

        $('#link_bank').val('');
        $('#image_url_bank').val('');
        $('.preview-banner-bank').attr('src', '/html/assets/images/img_upload.png');
    });
    $('.amortization-bank-partner .update-action').on('click', function (e) {
        e.preventDefault();

        $(this).hide();
        $(".amortization-bank-partner .box-display").slideUp();
        $(".amortization-bank-partner .box-update").slideDown();

        return false;
    });
    $('.amortization-bank-partner .add-action').on('click', function (e) {
        e.preventDefault();

        $(this).parent().slideUp();
        $(".amortization-bank-partner .box-update").slideDown();

        return false;
    });
    $(".amortization-bank-partner .cancel").on('click', function (e) {
        e.preventDefault();

        $(".amortization-bank-partner .box-display").slideDown();
        $('.amortization-bank-partner .add-action').parent().slideDown();
        $('.amortization-bank-partner .update-action').show();
        $(".amortization-bank-partner .box-update").slideUp();

        return false;
    });
    $('#form_amortization_bank_partner').validate({
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
    //------------- start section amortization_banner ---------------

    $('.amortization_banner_update').on('click', function (e) {
        e.preventDefault();

        $(this).hide();
        $(".banner-display").slideUp();
        $(".banner-update").slideDown();

        return false;
    });
    $('.amortization_banner_add').on('click', function (e) {
        e.preventDefault();

        $(this).slideUp();
        $(".banner-update").slideDown();

        return false;
    });
    $(".banner-update .cancel").on('click', function (e) {
        e.preventDefault();

        $('.banner-display').slideDown();
        $('.amortization_banner_add').slideDown();
        $('.amortization_banner_update').show();
        $(".banner-update").slideUp();

        return false;
    });

    $('#form_banner_update').validate({
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

    $('.amortization-process-buy .update-action').on('click', function (e) {
        e.preventDefault();

        $(this).hide();
        $(".amortization-process-buy .box-display").slideUp();
        $(".amortization-process-buy .box-update").slideDown();

        return false;
    });
    $('.amortization-process-buy .add-action').on('click', function (e) {
        e.preventDefault();

        $(this).parent().slideUp();
        $(".amortization-process-buy .box-update").slideDown();

        return false;
    });
    $(".amortization-process-buy .cancel").on('click', function (e) {
        e.preventDefault();

        $(".amortization-process-buy .box-display").slideDown();
        $('.amortization-process-buy .add-action').parent().slideDown();
        $('.amortization-process-buy .update-action').show();
        $(".amortization-process-buy .box-update").slideUp();

        return false;
    });
    $('#form_amortization_process_buy_update').validate({
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


    $('.amortization-info .update-action').on('click', function (e) {
        e.preventDefault();

        $(this).hide();
        $(".amortization-info .box-display").slideUp();
        $(".amortization-info .box-update").slideDown();

        return false;
    });
    $('.amortization-info .add-action').on('click', function (e) {
        e.preventDefault();

        $(this).parent().slideUp();
        $(".amortization-info .box-update").slideDown();

        return false;
    });
    $(".amortization-info .cancel").on('click', function (e) {
        e.preventDefault();

        $(".amortization-info .box-display").slideDown();
        $('.amortization-info .add-action').parent().slideDown();
        $('.amortization-info .update-action').show();
        $(".amortization-info .box-update").slideUp();

        return false;
    });
    $('#form_amortization_info_update').validate({
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


$(document).on('click', '.action-delete-uline', function () {
    $(this).closest('li').remove();
    if ($('#form_amortization_line .list-category li').length == 0) {
        add_rule_line();
    }
});
function add_line() {
    var name = $('#lines_name').val();
    var link = $('#lines_link').val();
    if (name=='' || link=='') {
        if (link=='') {
            $('#lines_link-error').show();
            $('#lines_link').focus()
        }
        if (name=='') {
            $('#lines_name-error').show();
            $('#lines_name').focus();
        }
        return false;
    }
    var now = $.now();
    var html = '<li>' +
        '<div class="col-md-3 name-cate">'+name+'</div>' +
        '<input type="hidden" value="0" name="lines['+now+'][id]">' +
        '<input type="hidden" value="'+name+'" name="lines['+now+'][name]">' +
        '<input type="hidden" value="'+link+'" name="lines['+now+'][link]">' +
        '<div class="col-md-8 link">' +
        '<a href="'+link+'" title="'+name+'">'+link+'</a>' +
        '</div>' +
        '<div class="col-md-1 text-right pull-right">' +
        '<a class="tooltip action-delete-uline">' +
        '<i class="fa fa-times action-delete" aria-hidden="true"></i>' +
        '<span class="tooltiptext">Xóa</span>' +
        '</a>' +
        '</div>' +
        '</li>';

    $('#form_amortization_line .list-category').prepend(html);
    $('#lines_name').val('').focus();
    $('#lines_link').val('');

    remove_rule_line();
}
function add_rule_line() {
    $( "#lines_name" ).rules( "add", {
        required: true,
        messages: {
            required: "Vui lòng nhập tên ngành hành",
        }
    });
    $( "#lines_link" ).rules( "add", {
        required: true,
        messages: {
            required: "Vui lòng nhập link liên kết ngành hàng",
        }
    });
}
function remove_rule_line() {
    $( "#lines_name" ).rules( "remove" );
    $( "#lines_link" ).rules( "remove" );
}