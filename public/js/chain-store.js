$(document).ready(function() {
    get_provinces('.provinces');

    $('textarea.value').ckeditor();

    $('#form_introduction_update').validate({
        ignore: ".ignore",
        rules: {
            value: "required",
        },
        messages: {
            value: "Vui lòng nhập nội dung giới thiệu",
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

    $('.add-introduction').on('click', function () {
        $(this).parent().slideUp();
        $(".cs-introduction .update-content").slideDown();
    });
    $('.update-introduction').on('click', function () {
        $(this).hide();
        $('.cs-introduction .content-display').slideUp();
        $(".cs-introduction .update-content").slideDown();
    });
    $('#form_introduction_update .cancel').on('click', function (e) {
        e.preventDefault();

        $('.update-introduction').show();
        $('.add-introduction').parent().slideDown();
        $('.cs-introduction .content-display').slideDown();
        $(".cs-introduction .update-content").slideUp();
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
    $('#form_banner_update .cancel').on('click', function (e) {
        e.preventDefault();

        $('.update-banner').show();
        $('.add-banner').parent().slideDown();
        $('.cs-banner .banner-display').slideDown();
        $(".cs-banner .banner-update").slideUp();
    });
    $('.update-banner').on('click', function () {
        $(this).hide();
        $(".cs-banner .banner-display").slideUp();
        $(".cs-banner .banner-update").slideDown();
    });
    $('.add-banner').on('click', function () {
        $(this).parent().slideUp();
        $(".cs-banner .banner-update").slideDown();
    });


    //-------------------------

    $('#form_process_banner_update').validate({
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
    $('#form_process_banner_update .cancel').on('click', function (e) {
        e.preventDefault();

        $('.update-process-banner').show();
        $('.add-process-banner').parent().slideDown();
        $('.cs-process-banner .banner-display').slideDown();
        $(".cs-process-banner .banner-update").slideUp();
    });
    $('.update-process-banner').on('click', function () {
        $(this).hide();
        $(".cs-process-banner .banner-display").slideUp();
        $(".cs-process-banner .banner-update").slideDown();
    });
    $('.add-process-banner').on('click', function () {
        $(this).parent().slideUp();
        $(".cs-process-banner .banner-update").slideDown();
    });

    //----------------------






    $('.add-opening').on('click', function () {
        $(this).hide();
        $('.cs-opening .display-list').slideUp();
        $('.cs-opening .update-list').slideDown();
    });
    $('.add-active').on('click', function () {
        $(this).hide();
        $('.cs-active .display-list').slideUp();
        $('.cs-active .update-list').slideDown();
    });
    $('.add-opening-none').on('click', function () {
        $(this).slideUp();
        $('.cs-opening .update-list').slideDown();
    });
    $('.add-active-none').on('click', function () {
        $(this).slideUp();
        $('.cs-active .update-list').slideDown();
    });
    $('#form_cs_opening .cancel').on('click', function (e) {
        e.preventDefault();

        $('#opening_id').val(0);
        $('#form_cs_opening')[0].reset();
        if ($('.add-opening-none').length > 0) {
            $('.cs-opening .display-list').hide();
            $('.add-opening-none').slideDown();
        } else {
            $('.add-opening').show();
            $('.cs-opening .display-list').slideDown();
        }
        $('.cs-opening .update-list').slideUp();
    });
    $('#form_cs_active .cancel').on('click', function (e) {
        e.preventDefault();

        $('#active_id').val(0);
        $('#form_cs_active')[0].reset();
        if ($('.add-active-none').length > 0) {
            $('.cs-active .display-list').hide();
            $('.add-active-none').slideDown();
        } else {
            $('.add-active').show();
            $('.cs-active .display-list').slideDown();
        }
        $('.cs-active .update-list').slideUp();
    });
    $('.action-update-opening').on('click', function () {
        var item = _opening[$(this).attr('data-id')];

        $('#form_cs_opening #opening_id').val(item.id);
        $('#form_cs_opening .name').val(item.name);
        $('#form_cs_opening .districts').attr('data-id', item.district_id);
        $('#form_cs_opening .wards').attr('data-id', item.ward_id);
        $('#form_cs_opening .provinces').val(item.province_id).trigger('change');
        $('#form_cs_opening .address').val(item.address);
        $('#form_cs_opening .embed_map').val(item.embed_map);
        $('#form_cs_opening label.error').hide();

        $('.add-opening').click();
    });
    $('.action-update-active').on('click', function () {
        var item = _active[$(this).attr('data-id')];

        $('#form_cs_active #active_id').val(item.id);
        $('#form_cs_active .name').val(item.name);
        $('#form_cs_active .districts').attr('data-id', item.district_id);
        $('#form_cs_active .wards').attr('data-id', item.ward_id);
        $('#form_cs_active .provinces').val(item.province_id).trigger('change');
        $('#form_cs_active .address').val(item.address);
        $('#form_cs_active .phone').val(item.phone);
        $('#form_cs_active .opening_time').val(item.opening_time);
        $('#form_cs_active .embed_map').val(item.embed_map);

        $('.add-active').click();
    });
    $('#form_cs_opening').validate({
        ignore: ".ignore",
        rules: {
            name: "required",
            province_id: "required",
            district_id: "required",
            ward_id: "required",
            address: "required",
        },
        messages: {
            name: "Vui lòng nhập tên siêu thị",
            province_id: "Vui lòng chọn tỉnh thành phố",
            district_id: "Vui lòng chọn quận huyện",
            ward_id: "Vui lòng phường xã",
            address: "Vui lòng nhập địa chỉ",
        },
        submitHandler: function(form) {
            // do other things for a valid form
            var data = $(form).serializeArray();
            var url = $(form).attr('action');
            $.post(url, data).done(function(data){
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
    $('#form_cs_active').validate({
        ignore: ".ignore",
        rules: {
            name: "required",
            province_id: "required",
            district_id: "required",
            ward_id: "required",
            address: "required",
            phone: "required",
            opening_time: "required"
        },
        messages: {
            name: "Vui lòng nhập tên siêu thị",
            province_id: "Vui lòng chọn tỉnh thành phố",
            district_id: "Vui lòng chọn quận huyện",
            ward_id: "Vui lòng phường xã",
            address: "Vui lòng nhập địa chỉ",
            phone: "Vui lòng nhập số điện thoại",
            opening_time: "Vui lòng nhập thời gian mở cửa",
        },
        submitHandler: function(form) {
            // do other things for a valid form
            var data = $(form).serializeArray();
            var url = $(form).attr('action');
            $.post(url, data).done(function(data){
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