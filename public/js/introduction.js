$(document).ready(function() {
    $('textarea.value').ckeditor();

    $('.introduction-commodity .update-action').on('click', function (e) {
        e.preventDefault();

        $(this).hide();
        $(".introduction-commodity .box-display").slideUp();
        $(".introduction-commodity .box-update").slideDown();

        return false;
    });
    $('.introduction-commodity .add-action').on('click', function (e) {
        e.preventDefault();

        $(this).parent().slideUp();
        $(".introduction-commodity .box-update").slideDown();

        return false;
    });
    $(".introduction-commodity .cancel").on('click', function (e) {
        e.preventDefault();

        $(".introduction-commodity .box-display").slideDown();
        $('.introduction-commodity .add-action').parent().slideDown();
        $('.introduction-commodity .update-action').show();
        $(".introduction-commodity .box-update").slideUp();

        return false;
    });
    $('#form_introduction_commodity_update').validate({
        ignore: ".ignore",
        rules: {
            'description[value]': "required"
        },
        messages: {
            'description[value]': "Vui lòng nhập nội dung mô tả"
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

    $('.introduction-sales .update-action').on('click', function (e) {
        e.preventDefault();

        $(this).hide();
        $(".introduction-sales .box-display").slideUp();
        $(".introduction-sales .box-update").slideDown();

        return false;
    });
    $('.introduction-sales .add-action').on('click', function (e) {
        e.preventDefault();

        $(this).parent().slideUp();
        $(".introduction-sales .box-update").slideDown();

        return false;
    });
    $(".introduction-sales .cancel").on('click', function (e) {
        e.preventDefault();

        $(".introduction-sales .box-display").slideDown();
        $('.introduction-sales .add-action').parent().slideDown();
        $('.introduction-sales .update-action').show();
        $(".introduction-sales .box-update").slideUp();

        return false;
    });
    $('#form_introduction_sales_update').validate({
        ignore: ".ignore",
        rules: {
            'banner[value]': "required",
            'banner[field]': "required",
            'description[value]': "required",
        },
        messages: {
            'banner[value]': "Vui lòng chọn ảnh",
            'banner[field]': "Vui lòng nhập liên kết",
            'description[value]': "Vui lòng nhập nội dung",
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

    $('.introduction-coverage .update-action').on('click', function (e) {
        e.preventDefault();

        $(this).hide();
        $(".introduction-coverage .box-display").slideUp();
        $(".introduction-coverage .box-update").slideDown();

        return false;
    });
    $('.introduction-coverage .add-action').on('click', function (e) {
        e.preventDefault();

        $(this).parent().slideUp();
        $(".introduction-coverage .box-update").slideDown();

        return false;
    });
    $(".introduction-coverage .cancel").on('click', function (e) {
        e.preventDefault();

        $(".introduction-coverage .box-display").slideDown();
        $('.introduction-coverage .add-action').parent().slideDown();
        $('.introduction-coverage .update-action').show();
        $(".introduction-coverage .box-update").slideUp();

        return false;
    });
    $('#form_introduction_coverage_update').validate({
        ignore: ".ignore",
        rules: {
            'banner[value]': "required",
            'banner[field]': "required",
            'description[value]': "required",
        },
        messages: {
            'banner[value]': "Vui lòng chọn ảnh",
            'banner[field]': "Vui lòng nhập liên kết",
            'description[value]': "Vui lòng nhập nội dung",
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

    $('.introduction_banner_update').on('click', function (e) {
        e.preventDefault();

        $(this).hide();
        $(".banner-display").slideUp();
        $(".banner-update").slideDown();

        return false;
    });
    $('.introduction_banner_add').on('click', function (e) {
        e.preventDefault();

        $(this).slideUp();
        $(".banner-update").slideDown();

        return false;
    });
    $(".banner-update .cancel").on('click', function (e) {
        e.preventDefault();

        $('.banner-display').slideDown();
        $('.introduction_banner_add').slideDown();
        $('.introduction_banner_update').show();
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

    $('.add-action-line').on('click', function () {
        add_line();
    });
});
$(document).on('click', '.action-delete-uline', function () {
    $(this).closest('li').remove();
    if ($('#form_introduction_commodity_update .list-category li').length == 0) {
        add_rule_line();
    }
});
function add_line() {
    var name = $('#lines_name').val();
    var link = $('#lines_link').val();
    var logo = $('#image_url_logo').val();
    if (logo=='' || name=='' || link=='') {
        if (logo=='') {
            $('#image_url_logo-error').show();
        }
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
        '<div class="col-md-2 name-cate">'+logo+'</div>' +
        '<div class="col-md-3">'+name+'</div>' +
        '<input type="hidden" value="0" name="lines['+now+'][id]">' +
        '<input type="hidden" value="'+logo+'" name="lines['+now+'][logo]">' +
        '<input type="hidden" value="'+name+'" name="lines['+now+'][name]">' +
        '<input type="hidden" value="'+link+'" name="lines['+now+'][link]">' +
        '<div class="col-md-6 link">' +
        '<a href="'+link+'" title="'+name+'">'+link+'</a>' +
        '</div>' +
        '<div class="col-md-1 text-right pull-right">' +
        '<a class="tooltip action-delete-uline">' +
        '<i class="fa fa-times action-delete" aria-hidden="true"></i>' +
        '<span class="tooltiptext">Xóa</span>' +
        '</a>' +
        '</div>' +
        '</li>';

    $('#form_introduction_commodity_update .list-category').prepend(html);
    $('#lines_name').val('').focus();
    $('#lines_link').val('');
    $('#image_url_logo').val('');
    $('#form_introduction_commodity_update .preview-banner-logo').attr('src', '/html/assets/images/img_upload.png');

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
function addCoverageBanner(filename) {
    $('#preview-coverage-banner').attr('src', _base_url+'/uploads/tmp/'+filename);
    $('#form_introduction_coverage_update .file_name').text(filename);
    $('#form_introduction_coverage_update #value').val(filename);
}
function addBanner(filename) {
    $('#preview-banner').attr('src', _base_url+'/uploads/tmp/'+filename);
    $('#form_banner_update .file_name').text(filename);
    $('#form_banner_update #value').val(filename);
}