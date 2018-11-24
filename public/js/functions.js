var _base_url = _base_url || '';
var _debug = typeof _debug == "undefined" ? 0 : _debug;

$(document).ready(function() {
    numeral.defaultFormat('0,0.[00]');

    $('.show-hide').on('click', function () {
        var tmp = $(this).attr('data-hide');
        $(tmp).hide();
        tmp = $(this).attr('data-show');
        $(tmp).show();
    });

    $('#form-quick-search').on('submit', function (e) {
        e.preventDefault();

        var href = _base_url;
        var by_search = $('[data-id="by-search"]').attr('title');
        var input_search = $('#input-search').val();
        if (!input_search) {
            return false;
        }
        if (by_search == 'Tên sản phẩm') {
            by_search = 'name';
            href += '/products';
        } else if (by_search == 'Mã sản phẩm') {
            by_search = 'sku';
            href += '/products';
        } else {
            by_search = 'code';
            href += '/order';
        }

        location.href = updateUrlParameter(by_search, input_search, href);
        return false;
    });
    /**
     * start change name page
     */
    var tmp = $('.item-menu.active-menu > a i.icon').attr('class');
    if (tmp) {
        tmp = tmp.replace("icon ", "fa ");
        $('.header .name_page a i').attr('class', tmp);

        tmp = $('.item-menu.active-menu > a > span').html();
        $('.header .name_page .title').html(tmp);
    }
    $('.header .name_page').show();
    /**
     * end change name page
     */

    if ($('.fm-number').length > 0) {
        init_fm_number('.fm-number');
    }

    if ($('fm-number').length > 0) {
        $('fm-number').each(function( index ) {
            $(this).html( numeral($(this).html()).format() );
        });
    }

    if ($('span.number').length > 0) {
        $('span.number').each(function( index ) {
            $(this).html( numeral($(this).html()).format() );
        });
    }

    $('.add-opening').on('click', function () {
        $(this).hide();
        $(this).closest(".box-left").find(".DisplayData").slideUp();
        $(this).closest(".box-left").find(".UpdateData").slideDown();
    });

    $('#limit').on('change', function(){
        var href = $(this).attr('data-href');
        location.href = updateUrlParameter('limit', $(this).val(), href);
    });
    if ($('#select_time').length > 0) {
        $('#select_time').on('change', function(){
            var option = $(this).val();
            var from, to;
            switch(option) {
                case 'to_day':
                    from = moment().format("DD-MM-YYYY");
                    to = from;
                    break;
                case 'this_week':
                    from = moment().startOf('week').add('d', 1).format("DD-MM-YYYY");
                    to = moment().format("DD-MM-YYYY");
                    break;
                case 'this_month':
                    from = moment().startOf('month').format("DD-MM-YYYY");
                    to = moment().format("DD-MM-YYYY");
                    break;
                case 'this_year':
                    from = moment().startOf('year').format("DD-MM-YYYY");
                    to = moment().format("DD-MM-YYYY");
                    break;
                case 'last_year':
                    from = moment().startOf('year').subtract(1, 'y').format("DD-MM-YYYY");
                    to = moment().startOf('year').subtract(1, 'd').format("DD-MM-YYYY");
                    break;
                default:
                    from = '';
                    to = '';
                    break;
            }

            $('#from_date').val(from);
            $('#to_date').val(to);
        });
    }
});
function init_select_time_home(element, show_time) {
    $(element).on('change', function(){
        var option = $(this).val();
        var from, to, type;
        switch(option) {
            case 'to_day':
                type = 'hour';
                from = moment().format("DD-MM-YYYY");
                to = from;
                break;
            case 'this_week':
                type = 'day';
                from = moment().startOf('week').add('d', 1).format("DD-MM-YYYY");
                to = moment().format("DD-MM-YYYY");
                break;
            case 'this_month':
                type = 'day';
                from = moment().startOf('month').format("DD-MM-YYYY");
                to = moment().format("DD-MM-YYYY");
                break;
            case 'this_year':
                type = 'month';
                from = moment().startOf('year').format("DD-MM-YYYY");
                to = moment().format("DD-MM-YYYY");
                break;
            case 'last_year':
                type = 'month';
                from = moment().startOf('year').subtract(1, 'y').format("DD-MM-YYYY");
                to = moment().startOf('year').subtract(1, 'd').format("DD-MM-YYYY");
                break;
            default:
                from = '';
                to = '';
                break;
        }

        $(show_time).html(from+' - '+to);
        $.get(_base_url+'/total-revenue', {from:from, to:to, type:type}, function (data) {
            // var daTacuata = [28, 48, 40, 19, 86, 27, 90];
            // var labelscuata = ["0", "01/12/16", "01/01/17", "01/02/17", "01/03/17", "01/03/17", "01/04/17"];
            init_chart_revenue(data.revenue, data.group_date);
        }, 'json');
    });
}
function init_chart_revenue(daTacuata, labelscuata) {
    $('.chart_revenue').html('<canvas class="areaChart" style="height: 496px; width: 992px;" height="496" width="992"></canvas>');

    /* ChartJS
             * -------
             * Here we will create a few charts using ChartJS
             */

    //--------------
    //- AREA CHART -
    //--------------
console.log(daTacuata, labelscuata);
    // Get context with jQuery - using jQuery's .get() method.
    var areaChartCanvas = $(".areaChart").get(0).getContext("2d");
    // This will get the first returned node in the jQuery collection.
    var areaChart = new Chart(areaChartCanvas);
    var areaChartData = {
        labels: labelscuata,
        datasets: [{
            label: "Doanh thu",
            fillColor: "rgba(39,182,200,0.9)",
            strokeColor: "rgba(39,182,200,0.8)",
            pointColor: "#27b6c8",
            pointStrokeColor: "rgba(39,182,200,1)",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(39,182,200,1)",
            data: daTacuata
        }]
    };

    var areaChartOptions = {
        //Boolean - If we should show the scale at all
        showScale: true,
        //Boolean - Whether grid lines are shown across the chart
        scaleShowGridLines: false,
        //String - Colour of the grid lines
        scaleGridLineColor: "rgba(0,0,0,.05)",
        //Number - Width of the grid lines
        scaleGridLineWidth: 1,
        //Boolean - Whether to show horizontal lines (except X axis)
        scaleShowHorizontalLines: true,
        //Boolean - Whether to show vertical lines (except Y axis)
        scaleShowVerticalLines: true,
        //Boolean - Whether the line is curved between points
        bezierCurve: true,
        //Number - Tension of the bezier curve between points
        bezierCurveTension: 0.3,
        //Boolean - Whether to show a dot for each point
        pointDot: false,
        //Number - Radius of each point dot in pixels
        pointDotRadius: 4,
        //Number - Pixel width of point dot stroke
        pointDotStrokeWidth: 1,
        //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
        pointHitDetectionRadius: 20,
        //Boolean - Whether to show a stroke for datasets
        datasetStroke: true,
        //Number - Pixel width of dataset stroke
        datasetStrokeWidth: 2,
        //Boolean - Whether to fill the dataset with a color
        datasetFill: true,
        //String - A legend template
        legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
        //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
        maintainAspectRatio: true,
        //Boolean - whether to make the chart responsive to window resizing
        responsive: true
    };
    areaChart.Line(areaChartData, areaChartOptions);
}

function init_fm_number(element) {
    $(element).on('keyup', function(event) {
        if (event.keyCode == 190 || event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40)
        {
            return;
        }

        var tmp = numeral( $(this).val() );
        $(this).val(tmp.format());
        if (tmp = $(this).attr('data-target')) {
            $(tmp).val(numeral( $(this).val() ).value()).valid();
        }
        if (tmp = $(this).attr('data-display')) {
            $(tmp).html(numeral( $(this).val() ).format())
        }
    });

    $(element).each(function( index ) {
        $(this).val( numeral($(this).val()).format() );
    });
}
function _log(data) {
    if (_debug) console.log(data);
}
function init_scrollbar(element) {
    $(element).mCustomScrollbar();
}
function init_clockpicker(element) {
    if ($(element).length > 0) {
        $(element).clockpicker({
            autoclose: true
        });
    }
}
function init_datepicker(element) {
    $(element).datepicker({ format: 'dd-mm-yyyy' });
}
function get_provinces(destination) {
    destination = destination || '#province_id';

    $.get(_base_url+'/location/get-provinces', function (res) {
        var html = '';
        $.each(res.data, function( id, name ) {
            html += '<option value="'+id+'">'+name+'</option>';
        });

        if ($(destination).hasClass('select2')) {
            $(destination).html('<option value=""></option>' + html);
            $(destination).select2("destroy");
            $(destination).val($(destination).attr('data-id'));
            init_select2(destination);
        } else {
            $(destination).html('<option value="">Chọn Tỉnh / Thành phố</option>' + html).val($(destination).attr('data-id'));
        }

        $(destination).trigger('change');
    }, 'json');
}
function get_brands(destination, callback) {
    destination = destination || '#brand_id';
    var id = $(destination).attr('data-id');

    $.get(_base_url+'/brands/get-options', function (res) {
        var html = '<option value=""></option>';
        _brands = res.data
        $.each(res.data, function( id, name ) {
            html += '<option value="'+id+'">'+name+'</option>';
        });
        $(destination).html(html).val(id).trigger('change');
        if (typeof callback !== undefined) {
            eval(callback+'()');
        }
    }, 'json');
}
/**
 * lây danh sach quan huyen theo tinh thanh
 * @param obj_province, string element tỉnh thành '#province_id' or '.province_id',
 * trên element này có thuộc tính data-destination='#district_id' doi tuong quan huyen nhan gia tri
 */
function get_districts_by_province(obj_province, select2) {
    var select2 = select2 || false;
    var destination = $(obj_province).attr('data-destination');
    var id = $(destination).attr('data-id');
    var html = '<option value="">Chọn Quận / Huyện</option>';

    if (!$(obj_province).val()) {
        $(destination).html(html).val(id).trigger('change');
        return;
    }

    $.get('/location/get-districts', {province_id: $(obj_province).val()}, function (res) {
        $.each(res.data, function( id, name ) {
            html += '<option value="'+id+'">'+name+'</option>';
        });
        $(destination).html(html).val(id).trigger('change');

        if (select2) init_select2(obj_province);

    }, 'json');
}
function get_wards_by_district(obj_district) {
    var destination = $(obj_district).attr('data-destination');
    var id = $(destination).attr('data-id');
    var html = '<option value="">Chọn Phường / Xã</option>';

    if (!$(obj_district).val()) {
        $(destination).html(html).val(id).trigger('change');
        return;
    }

    $.get('/location/get-wards', {district_id: $(obj_district).val()}, function (res) {
        $.each(res.data, function( id, name ) {
            html += '<option value="'+id+'">'+name+'</option>';
        });
        $(destination).html(html).val(id);
    }, 'json');
}
function init_chart_product(element, data) {
    var chart = AmCharts.makeChart(element, {
      "type": "pie",
      "startDuration": 0,
      "theme": "light",
      "addClassNames": true,
      labelsEnabled: false,
      autoMargins: false,
      marginTop: 20,
      marginBottom: 20,
      marginLeft: 10,
      marginRight: 10,
      pullOutRadius: 0,
      "legend":{
        "position":"right",
        "marginRight":50,
        "autoMargins":false
      },
      "innerRadius": "50%",
      "defs": {
        "filter": [{
          "id": "shadow",
          "width": "200%",
          "height": "200%",
          "feOffset": {
            "result": "offOut",
            "in": "SourceAlpha",
            "dx": 0,
            "dy": 0
          },
          "feGaussianBlur": {
            "result": "blurOut",
            "in": "offOut",
            "stdDeviation": 5
          },
          "feBlend": {
            "in": "SourceGraphic",
            "in2": "blurOut",
            "mode": "normal"
          }
        }]
      },
      "dataProvider": data,
      "valueField": "litres",
      "titleField": "country",
      "export": {
        "enabled": true
      }
    });
}
function init_chart_category(data) {
    var chart = AmCharts.makeChart("chartdiv_category", {
      "type": "pie",
      "startDuration": 0,
       "theme": "light",
      "addClassNames": true,
      labelsEnabled: false,
      autoMargins: false,
      marginTop: 20,
      marginBottom: 20,
      marginLeft: 10,
      marginRight: 10,
      pullOutRadius: 0,
      "legend":{
        "position":"right",
        "marginRight":50,
        "autoMargins":false
      },
      "innerRadius": "50%",
      "defs": {
        "filter": [{
          "id": "shadow",
          "width": "200%",
          "height": "200%",
          "feOffset": {
            "result": "offOut",
            "in": "SourceAlpha",
            "dx": 0,
            "dy": 0
          },
          "feGaussianBlur": {
            "result": "blurOut",
            "in": "offOut",
            "stdDeviation": 5
          },
          "feBlend": {
            "in": "SourceGraphic",
            "in2": "blurOut",
            "mode": "normal"
          }
        }]
      },
      "dataProvider": data,
      "valueField": "litres",
      "titleField": "country",
      "export": {
        "enabled": true
      }
    });
}
function init_select2(element) {
    $(element).select2({ allowClear: true, width: "100%" });
}
function init_select_date(element) {
    $(element).datetimepicker({
        format: 'DD-MM-YYYY'
    });
}
function init_icheck(obj) {
    if( $(obj).length ) {
        $(obj).iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_flat-green'
        });
    }
}
var $modal = $('#light_box-modal');
$(document).on( "click", ".light_box_href", function(e) {
    e.preventDefault();

    light_box_modal(this);
});
function light_box_modal(obj) {
    var _id = $(obj).attr('data-id') || 'light_box-modal';
    $('#'+_id).remove();
    $('body').append('<div id="'+_id+'" class="modal fade" data-backdrop="static"></div>');
    var $modal = $('#'+_id);

    // Display popup add related contact
    var href = $(obj).attr('href');
    // create the backdrop and wait for next modal to be triggered
    ajax_loading(true);
    $modal.load(href, '', function () {
        ajax_loading(false);
        $modal.modal();
    });
}
$(document).on('hidden.bs.modal', function (event) {
    if ($('.modal:visible').length) {
        $('body').addClass('modal-open');
    }
});

$(document).on( "submit", "#light_box-modal .frmAjaxSubmit", function(e) {
    e.preventDefault();

    ajax_loading(true);
    var frm = $(this);

    $.post( frm.attr('action'), frm.serialize(), function( response ) {
        ajax_loading(false);

        var jdata = null;
        var isJSON = true;
        try
        {
            jdata = $.parseJSON(response);
        }
        catch(err)
        {
            isJSON = false;
        }

        if (!isJSON) {
            $modal.html(response);
            return false;
        }

        if (jdata.result=="OK") {
            malert(jdata.messages, null, function () {
                $modal.modal('hide');
            });
        }
    });
});
function updateUrlParameter(key, value, url){
    if (!url) url = window.location.href;
    var re = new RegExp("([?&])" + key + "=.*?(&|#|$)(.*)", "gi"),
        hash;

    if (re.test(url)) {
        if (typeof value !== 'undefined' && value !== null)
            return url.replace(re, '$1' + key + "=" + value + '$2$3');
        else {
            hash = url.split('#');
            url = hash[0].replace(re, '$1$3').replace(/(&|\?)$/, '');
            if (typeof hash[1] !== 'undefined' && hash[1] !== null)
                url += '#' + hash[1];
            return url;
        }
    }
    else {
        if (typeof value !== 'undefined' && value !== null) {
            var separator = url.indexOf('?') !== -1 ? '&' : '?';
            hash = url.split('#');
            url = hash[0] + separator + key + '=' + value;
            if (typeof hash[1] !== 'undefined' && hash[1] !== null)
                url += '#' + hash[1];
            return url;
        }
        else
            return url;
    }
}
function ajax_loading(show) {
    if ($('#bg-load').length == 0) {
        $('body').append('<div id="bg-load" class="wrap-loader"><div id="container"><div id="loader"></div></div></div>');
    }
    if (show) {
        $('#bg-load').show();
    } else {
        $('#bg-load').hide();
    }
}
function init_rating(obj) {
    var options = {
        language: 'en',
        min: 0,
        stars: 5,
        showClear: false,
        starCaptions: {
            0: '0',
            0.5: '0.5',
            1: '1',
            1.5: '1.5',
            2: '2',
            2.5: '2.5',
            3: '3',
            3.5: '3.5',
            4: '4',
            4.5: '4.5',
            5: '5'
        }
    };
    $(obj).rating(options);
}
function malert(msg, title, callback, sbcallback) {
    title = title || 'Thông báo';
    callback = callback || function (e) {};

    if ($("#modal_alert").length == 0) {
        var html = ''+
            '<div id="modal_alert" class="DeleteModal modal fade" role="dialog">'+
            '<div class="modal-dialog"'+
            '<!-- Modal content-->'+
            '<div class="modal-content">'+
            '<div class="modal-header">\n' +
            '        <button type="button" class="close" data-dismiss="modal" style="color: #000;">&times;</button>\n' +
            '        <h4 class="modal-title" style="color: #333; text-align: left;">Thông báo</h4>\n' +
            '      </div>' +
            '<div class="content">'+
            '<h2 style="margin: 0px;"></h2>'+
            '<button type="button" class="btn btn-cancel" data-dismiss="modal" style="width: auto;"><i class="fa fa-undo" aria-hidden="true"></i> Thoát</button>'+
            '<button type="button" class="btn btn-del" data-dismiss="modal" style="width: auto;"><i class="fa fa-check" aria-hidden="true"></i> Đồng ý</button>'+
            '</div>'+
            '</div>'+
            '</div>'+
            '</div>';

        $( "body main" ).append(html);
    }

    $("#modal_alert .btn-del").unbind( "click" );
    if (sbcallback) {
        $("#modal_alert .btn-del").show();
        $("#modal_alert .btn-del").bind( "click", sbcallback );
    } else {
        $("#modal_alert .btn-del").hide();
    }

    $(".modal-title").html(title);
    $("#modal_alert .content h2").html(msg);

    $('#modal_alert').modal('show');
    $('#modal_alert').on('hidden.bs.modal', callback);
}
function show_pnotify(_title, _text, _type) {
    _title = _title || 'Thông báo';
    _type = _type || 'success';

    new PNotify({
        title: _title,
        text: _text,
        type: _type
    });
}
function alert_success(msg, callback) {
    callback = callback || function (e) {};

    if ($("#alert_success").length == 0) {
        var html = ''+
            '<div id="alert_success" class="SuccessModal modal fade" role="dialog">'+
            '<div class="modal-dialog">'+
            '<div class="modal-content ">'+
            '<button type="button" class="close" data-dismiss="modal">&times;</button>'+
        '<div class="content">'+
            '<i class="icon_success">&nbsp</i>'+
            '<h2></h2>'+
            '<button type="button" class="btn_next btn-save" data-dismiss="modal">Tiếp tục</button>'+
        '</div>'+
        '</div>'+
        '</div>'+
        '</div>';

        $( "body main" ).append(html);
    }

    $("#alert_success .content h2").html(msg);

    $('#alert_success').modal('show');
    $("#alert_success" ).unbind('hidden.bs.modal');
    $('#alert_success').on('hidden.bs.modal', callback);
}
function confirm_delete(msg, callback,icon) {
    msg = msg || 'Bạn có muốn xóa nội dung này?';
    callback = callback || function (e) {};
    icon = icon || 'icon_delete';
    if ($("#confirm_delete").length == 0) {
        var html = ''+
            '<div id="confirm_delete" class="DeleteModal modal fade" role="dialog">'+
            '<div class="modal-dialog"'+
            '<!-- Modal content-->'+
            '<div class="modal-content">'+
            '<button type="button" class="close" data-dismiss="modal">&times;</button>'+
            '<div class="content">'+
            '<i class="'+icon+'">&nbsp</i>'+
            '<h2></h2>'+
            '<button type="button" class="btn btn-cancel" data-dismiss="modal">Hủy</button>'+
            '<button type="button" class="btn btn-del" data-dismiss="modal">Đồng ý</button>'+
            '</div>'+
            '</div>'+
            '</div>'+
            '</div>';

        $( "body main" ).append(html);
    }

    $("#confirm_delete .content h2").html(msg);

    $('#confirm_delete').modal('show');
    $("#confirm_delete .btn-del").unbind( "click" );
    $("#confirm_delete .btn-del").bind( "click", callback );
}

$(function () {

    var browseImage = function() {
        $('.browse-image').click(function () {
            var name = $(this).attr('data-target');
            BrowseServer(name);
        });
    };
    browseImage();

    var browseImageList = function() {
        $('.browse-image-list').click(function () {
            var target = $(this).attr('data-target');
            BrowseServerList(target);
        });
    };
    browseImageList();
});

function BrowseServerList(target){
    var index = $(target).data('index') + 1;
    $(target).data('index',index);
    var name    = 'image_details_'+index;
    var preview = 'preview_image_details_'+index;
    var span = document.createElement('span');
    span.innerHTML =
        [
            '<img class="'+preview+'" style="height: 140px; border: 1px solid #2bc5f8; margin: 7px" title=""/>',
            '<input type="hidden" id="'+name+'" name="image_details[]" data-preview=".'+preview+'" >'
        ].join('');

    span.addEventListener('click', function (e) {
        if (e.offsetX > span.offsetWidth-15) {
            $(this).remove();
        }
    });

    $(target).append(span);

    var config = {};
    config.startupPath = 'Images:/banner/';
    var finder = new CKFinder(config);
    finder.selectActionFunction = SetFileField;
    finder.selectActionData = name;
    finder.callback = function( api ) {
        api.disableFolderContextMenuOption( 'Batch', true );
    };
    finder.popup();
}

function BrowseServer(name) {
    var config = {};
    config.startupPath = 'Images:/banner/';
    var finder = new CKFinder(config);
    finder.selectActionFunction = SetFileField;
    finder.selectActionData = name;
    finder.callback = function( api ) {
        api.disableFolderContextMenuOption( 'Batch', true );
    };
    finder.popup();
}

function SetFileField(fileUrl, data) {
    var name = '';
    try {
        var hostname = (new URL(fileUrl)).hostname;
        name = fileUrl.split(hostname);
        name = name[name.length - 1];
    } catch (_) {
        name = fileUrl;
    }

    $('#' + data["selectActionData"]).val(name);

    var preview = $('#' + data["selectActionData"]).attr('data-preview');
    $(preview).attr('src', name);

    preview = $('#' + data["selectActionData"]).attr('data-url');
    $(preview).val('');
}

function get_link_brand(name, id) {
    return _outside_url+'/'+slug(name)+'-b'+id+'.html';
}
function get_link_product(name, id) {
    return _outside_url+'/'+slug(name)+'-'+id+'.html';
}
function slug(str) {
    str = str.replace(/^\s+|\s+$/g, ''); // trim
    str = str.toLowerCase();

    // remove accents, swap ñ for n, etc
    var from = "ãàáäâẽèéëêìíïîõòóöôùúüûñç·/_,:;";
    var to   = "aaaaaeeeeeiiiiooooouuuunc------";
    for (var i=0, l=from.length ; i<l ; i++) {
        str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
    }

    str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
        .replace(/\s+/g, '-') // collapse whitespace and replace by -
        .replace(/-+/g, '-'); // collapse dashes

    return str;
};

function range_filter_remove(obj) {
    var name = $(obj).attr('data-name');
    var value = $(obj).closest('tr').find('.'+name).val();
    var tbody = $(obj).closest('tbody');
    if (tbody.find('tr').length > 1) {
        $(obj).closest('tr').remove();
        tbody.append('<input name="delete_'+name+'[]" value="'+value+'" type="hidden">');
        return true;
    }
    return false;
}
function remove_row(obj) {
    $(obj).closest('tr').remove();
}
function range_filter_add(obj) {
    var time = $.now();
    var row = '<tr class="'+time+'">\n' +
        '                                <td class="position">\n' +
        '                                    <input class="range_id" name="ranges['+time+'][range_id]" value="0" type="hidden">\n' +
        '                                    <input class="form-control" name="ranges['+time+'][position]" value="" type="text">\n' +
        '                                    <label class="position-error error"></label>\n' +
        '                                </td>\n' +
        '                                <td class="range_name">\n' +
        '                                    <input class="form-control" name="ranges['+time+'][range_name]" value="" type="text">\n' +
        '                                    <label class="range_name-error error"></label>\n' +
        '                                </td>\n' +
        '                                <td class="from">\n' +
        '                                    <input class="form-control fm-number" name="ranges['+time+'][from]" value="" type="text">\n' +
        '                                    <label class="from-error error"></label>\n' +
        '                                </td>\n' +
        '                                <td class="to">\n' +
        '                                    <input class="form-control fm-number" name="ranges['+time+'][to]" value="" type="text">\n' +
        '                                    <label class="to-error error"></label>\n' +
        '                                </td>\n' +
        '                                <td class="actions">\n' +
        '                                    <a href="javascript:void(0)" onclick="range_filter_add(this)" title="Thêm"\n' +
        '                                       class="btn btn-xs btn-default"><i class="fa fa-plus"></i></a>\n' +
        '                                    <a href="javascript:void(0)" onclick="range_filter_remove(this)"\n' +
        '                                       data-name="range_id" title="Xoá"\n' +
        '                                       class="btn btn-xs btn-default" data-button-type="delete"><i class="fa fa-trash"></i></a>\n' +
        '                                </td>\n' +
        '                            </tr>';
    $(obj).closest('tbody').append(row);

    init_fm_number('.'+time+' .fm-number');

    return false;
}
function get_html_category_filter(id, name) {
    return '<tr>\n' +
        '                            <td>\n' +
        '                                <input class="categories" name="categories[]" value="'+id+'" type="hidden">\n' + name +
        '                            </td>\n' +
        '                            <td class="actions">\n' +
        '                                <a href="javascript:void(0)" onclick="remove_row(this)" title="Xoá"\n' +
        '                                   class="btn btn-xs btn-default" data-button-type="delete"><i class="fa fa-trash"></i></a>\n' +
        '                            </td>\n' +
        '                        </tr>';
}
function variant_filter_add(obj) {
    var time = $.now();
    var row = '<tr>\n' +
        '                                <td class="position">\n' +
        '                                    <input class="variant_id" name="variants['+time+'][variant_id]" value="0" type="hidden">\n' +
        '                                    <input class="form-control" name="variants['+time+'][position]" value="0" type="text">\n' +
        '                                    <label class="position-error error"></label>\n' +
        '                                </td>\n' +
        '                                <td class="variant">\n' +
        '                                    <input class="form-control" name="variants['+time+'][variant]" value="" type="text">\n' +
        '                                    <label class="variant-error error"></label>\n' +
        '                                </td>\n' +
        '                                <td class="actions">\n' +
        '                                    <a href="javascript:void(0)" onclick="variant_filter_add(this)" title="Thêm"\n' +
        '                                       class="btn btn-xs btn-default"><i class="fa fa-plus"></i></a>\n' +
        '                                    <a href="javascript:void(0)" onclick="range_filter_remove(this)"\n' +
        '                                       data-name="variant_id" title="Xoá"\n' +
        '                                       class="btn btn-xs btn-default" data-button-type="delete"><i class="fa fa-trash"></i></a>\n' +
        '                                </td>\n' +
        '                            </tr>';
    $(obj).closest('tbody').append(row);

    return false;
}