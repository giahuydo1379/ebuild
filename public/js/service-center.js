$(document).ready(function() {

    get_provinces('.provinces');

    //------------- start section shopping_guide_banner ---------------

    $('.service_center_banner_update').on('click', function (e) {
        e.preventDefault();

        $(this).hide();
        $(".banner-display").slideUp();
        $(".banner-update").slideDown();

        return false;
    });
    $('.service_center_banner_add').on('click', function (e) {
        e.preventDefault();

        $(this).slideUp();
        $(".banner-update").slideDown();

        return false;
    });
    $(".banner-update .cancel").on('click', function (e) {
        e.preventDefault();

        $('.banner-display').slideDown();
        $('.service_center_banner_add').slideDown();
        $('.service_center_banner_update').show();
        $(".banner-update").slideUp();

        return false;
    });

    $('#form_service_center_banner_update').validate({
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

    $('.service-center-description .update-action').on('click', function (e) {
        e.preventDefault();

        $(this).hide();
        $(".service-center-description .box-display").slideUp();
        $(".service-center-description .box-update").slideDown();

        return false;
    });
    $('.service-center-description .add-action').on('click', function (e) {
        e.preventDefault();

        $(this).parent().slideUp();
        $(".service-center-description .box-update").slideDown();

        return false;
    });
    $(".service-center-description .cancel").on('click', function (e) {
        e.preventDefault();

        $(".service-center-description .box-display").slideDown();
        $('.service-center-description .add-action').parent().slideDown();
        $('.service-center-description .update-action').show();
        $(".service-center-description .box-update").slideUp();

        return false;
    });
    $('#form_service_center_description_update').validate({
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
    
    //-------------------------------------

    //----------------------

    $('.add-opening').on('click', function () {
        $(this).hide();
        $('.cs-opening .display-list').slideUp();
        $('.cs-opening .update-list').slideDown();
    });

    $('.add-opening-none').on('click', function () {
        $(this).slideUp();
        $('.cs-opening .update-list').slideDown();
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

    $('.action-update-opening').on('click', function () {
        var item = _opening[$(this).attr('data-id')];

        $('#form_cs_opening #opening_id').val(item.id);
        $('#form_cs_opening .name').val(item.name);
        //$('#form_cs_opening .brand_id').val(item.brand_id);
        $('#form_cs_opening .districts').attr('data-id', item.district_id);
        $('#form_cs_opening .wards').attr('data-id', item.ward_id);
        $('#form_cs_opening .provinces').val(item.province_id).trigger('change');
        $('#form_cs_opening .address').val(item.address);
        $('#form_cs_opening .phone').val(item.phone);
        $('#form_cs_opening .opening_time').val(item.opening_time);
        $('#form_cs_opening .embed_map').val(item.embed_map);
        $('#form_cs_opening label.error').hide();

        $('.add-opening').click();
    });

    $('#form_cs_opening').validate({
        ignore: ".ignore",
        rules: {
            name: "required",
            province_id: "required",
            district_id: "required",
            ward_id: "required",
            address: "required",
            phone: "required",
            opening_time: "required",
            embed_map: "required",
        },
        messages: {
            name: "Vui lòng nhập tên trung tâm",
            province_id: "Vui lòng chọn tỉnh thành phố",
            district_id: "Vui lòng chọn quận huyện",
            ward_id: "Vui lòng phường xã",
            address: "Vui lòng nhập địa chỉ",
            phone: "Vui lòng nhập số điện thoại",
            opening_time: "Vui lòng nhập thời gian mở cửa",
            embed_map: "Vui lòng nhập embed map",
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


