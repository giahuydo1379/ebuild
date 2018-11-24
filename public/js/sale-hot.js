$(document).ready(function() {
    $('.clockpicker').clockpicker({
        autoclose: true
    });
    init_datepicker('.datepicker');

    $('.btn-start').on('click', function () {
        var id = $(this).attr('data-id');
        malert('Bạn có muốn kích hoạt chương trình khuyến mãi này không?', 'Xác nhận kích hoạt chương trình khuyến mãi', null, function () {
            ajax_loading(true);

            $.post(_base_url+'/sale-hot/update-status', {id: id, status: 1}, function () {
                ajax_loading(false);
                location.reload();
            });
        });
    });
    $('.btn-stop').on('click', function () {
        var id = $(this).attr('data-id');
        malert('Bạn có muốn dừng chương trình khuyến mãi này không?', 'Xác nhận dừng chương trình khuyến mãi', null, function () {
            ajax_loading(true);

            $.post(_base_url+'/sale-hot/update-status', {id: id, status: 0}, function () {
                ajax_loading(false);
                location.reload();
            });
        });
    });

    $('input.slider-toggle').each(function (key, msg) {
        if ($(this).is(':checked')) {
            $(this).closest('div').find('.tooltiptext').text('Đã kích hoạt');
        } else {
            $(this).closest('div').find('.tooltiptext').text('Chưa kích hoạt');
        }
    });

    $('.delete-action').on('click', function () {
        var obj = this;
        confirm_delete("Bạn có muốn xóa, chương trình khuyến mãi này không?", function () {
            $.post(_base_url+'/sale-hot/delete', {
                id: $(obj).attr('data-id'),
            }, function(data){
                alert_success(data.msg, function () {
                    if(data.rs == 1)
                    {
                        location.reload();
                    }
                });
            });
        });
    });

    $('.LoadProduct .cancel').unbind('click');
    $('.LoadProduct .cancel').on('click', function (e) {
        e.preventDefault();

        $('.display-product').slideDown();

        $('.add-giff-product').hide();

        if ($('.giff-item').length > 0) {
            $('.LoadProduct .TitleCreate').hide();
            $('.LoadProduct .TitleDisplay').show();

            $('.link.add-giff-product').show();
        } else {
            $('.LoadProduct .TitleCreate').show();
            $('.LoadProduct .TitleDisplay').hide();

            $('.no-banner.add-giff-product').show();
        }
        $('.LoadProduct .CreateLoad').slideUp();

        $('.list-products .color').html("");

        return false;
    });


    $('.add-action').on('click', function () {
        $(this).hide();
        $('.banner-display').slideUp();
        $('.banner-update').slideDown();

        $('.TitleCreate').show();
        $('.TitleDisplay').hide();
        $('.TitleUpdate').hide();
    });

    $('.add-action-none').on('click', function () {
        $(this).parent().slideUp();
        $('.banner-update').slideDown();
        $('.link.add-giff-product').hide();
    });

    $('.banner-update .main-cancel').on('click', function (e) {
        e.preventDefault();

        if ($('#is_reload').val() == '1') {
            location.reload();
            return false;
        }
        $('#is_next').val(0);
        $('#form_update')[0].reset();
        $('#id').val(0);
        $('#image_location').val('');
        $('#image_url').val('');
        $('#form_update .preview-banner').attr('src', '/html/assets/images/image-salehot.png');
        $('#form_update .not_update').show();

        $('.add-action-none').parent().slideDown();
        $('.banner-display').slideDown();

        if ($('.add-action-none').length > 0) {
            $('.TitleCreate').show();
            $('.TitleDisplay').hide();
            $('.TitleUpdate').hide();
        } else {
            $('.TitleCreate').hide();
            $('.TitleDisplay').show();
            $('.TitleUpdate').hide();

            $('.add-action').show();
        }

        $('.banner-update').slideUp();
    });

    $('.update-action').on('click', function () {
        var item = _objects[$(this).attr('data-id')];

        $('.add-action').click();
        $('.TitleCreate').hide();
        $('.TitleDisplay').hide();
        $('.TitleUpdate').show();

        var obj_form = '#form_update';

        $('label.error').hide();
        $(obj_form + ' #is_next').val(0);
        $(obj_form + ' #id').val(item.id);
        $(obj_form + ' #name').val(item.name);
        $(obj_form + ' #description').val(item.description);
        $(obj_form + ' #from_time').val(item.from_time);
        $(obj_form + ' #from_date').val(item.from_date);
        $(obj_form + ' #to_time').val(item.to_time);
        $(obj_form + ' #to_date').val(item.to_date);
        $(obj_form + ' #image_location').val(item.image_location);
        $(obj_form + ' #image_url').val(item.image_url);
        $(obj_form + ' #link').val(item.link);
        $(obj_form + ' .preview-banner').attr('src', item.image_url+item.image_location);
        $(obj_form + ' #position').val(item.type).trigger('change');

        $(obj_form + ' .not_update').hide();

        if (item.status) {
            $(obj_form + ' #status').attr('checked', 'checked');
        } else {
            $(obj_form + ' #status').removeAttr('checked');
        }
    });

    function init_form_update(obj_form) {
        $(obj_form).validate({
            ignore: ".ignore",
            rules: {
                name: "required",
                description: "required",
                link: "required",
                times: "required",
                image_location: "required",
            },
            messages: {
                name: "Vui lòng nhập tên chương trình",
                description: "Nhập mô tả chương trình",
                link: "Nhập link liên kết chương trình",
                times: "Nhập số lượt sử dụng",
                image_location: "Chọn ảnh chương trình",
            },
            submitHandler: function(form) {
                submitHandler(form);

                return false;
            }
        });
    }

    function submitHandler(form) {
        // do other things for a valid form
        ajax_loading(true);
        var data = $(form).serializeArray();
        var url = $(form).attr('action');
        $.post(url, data).done(function(data){
            ajax_loading(false);
            if(data.rs == 1)
            {
                $('#is_reload').val(1);
                $(form)[0].reset();
                alert_success(data.msg, function () {
                    if ($('#is_next').val()=='1') {
                        $('.add-action').click();
                        $('#form_update')[0].reset();
                        $('#id').val(0);
                        $('#image_location').val('');
                        $('#image_url').val('');
                    } else {
                        location.reload();
                    }
                });
            } else {
                alert_success(data.msg);
                if (data.errors) {
                    $.each(data.errors, function (key, msg) {
                        $('#'+key+'-error').html(msg).show();
                    });
                }
            }
        });
    }

    init_form_update('#form_update');
});