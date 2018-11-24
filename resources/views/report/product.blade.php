@extends('layouts.master')
<?php
$ordering_options = \App\Helpers\General::get_ordering_options();
$version_js = \App\Helpers\General::get_version_js();
?>
@section('content')
    <div class="wrap_view col-md-">
        <section class="section section-homepage view_donhang">
            <h3 class="title-section">Báo cáo</h3>
            <div class="panel box-panel">
                <div class="home-management" >
                    @include('report.tabs')

                    <div id="tab1" class="tabcontent active" style="display: block;">
                        <div class="create-landingpage CreatePromotion" style="display: block;">
                            <form class="form-create home-manager no-padding search_donhang" id="form_export" method="post" action="{{ route("report.export") }}">
                                <div class="search_level row" style="display: block">
                                    <div class="wrap_search">
                                        <div class="status">
                                            <p class="sub_title"><i class="icon-status">&nbsp</i> <span>Trạng thái đơn hàng</span></p>
                                            <div class="row">                                   
                                                <div class="col-md-3 order_status_1">
                                                </div>
                                                <div class="col-md-3 order_status_2">
                                                </div>
                                                <div class="col-md-3 order_status_3">
                                                </div>
                                                <div class="col-md-3 order_status_4">
                                                </div>
                                            </div>
                                        </div>
                                    </div>                                    
                                </div>
                                <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Kiểu báo cáo</label>
                                                {!! Form::select("type", [''=>'Kiểu báo cáo']+$type, '', ['id' => 'type', 'class' => 'form-control']) !!}
                                                <label id="type-error" class="error" for="type" style="display: none;"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Từ ngày</label>
                                                <input name="from_date" id="from_date" class="form-control datepicker" size="20" type="text" value="" placeholder="Từ ngày">
                                                <label id="ordering-error" class="error" for="ordering" style="display: none;"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="">Đến ngày</label>
                                            <input name="to_date" id="to_date" class="form-control datepicker" size="20" type="text" value="" placeholder="Đến ngày">
                                            <label id="to_date-error" class="error" for="to_date" style="display: none;"></label>
                                        </div>
                                    </div>
                                <div class="action text-right border-bottom">
                                    <input type="hidden" name="report_type" value="<?=$report_type?>">
                                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                    <button type="submit" value="" class="btn btn_primary">Xuất báo cáo</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('after_scripts')
<script type="text/javascript">
    var _block = '{!! @$report_type!!}';
    var $orderStatus    = <?=json_encode($orderStatus)?>;
    var $status         = <?=!empty($params['status'])?json_encode($params['status']):'{}'?>;
    $(document).ready(function() {
        
        $(".swiper-slide").click(function (e){
            window.location.href = $(this).find('a').attr('href');
        });

        if (typeof _block != 'undefined' && _block) {
            $('.swiper-slide .'+_block).attr('href', 'javascript:void(0)').addClass('active');

            var tmp = $('.tab.swiper-wrapper').width() - $('.'+_block).parent().offset();
            if (tmp<0) {
                $('.tab.swiper-wrapper').css('transform', 'translate3d('+tmp+'px, 0px, 0px)');
            }
        }
        set_data_order_status();
        init_datepicker('.datepicker');
        
        $('#form_export').validate({
            ignore: ".ignore",
            rules: {
                type:'required',
                from_date: 'required',
                to_date: 'required'
            },
            messages: {
                type: 'Vui lòng chọn loại báo cáo',
                from_date: 'Chọn Ngày bắt đầu',
                to_date: 'Chọn ngày kết thúc'
            },
            submitHandler: function(form) {
                var data = $(form).serializeArray();
                
                var count_status = 0;
                $.each(data,function(k,v){
                    if(v.name == 'status[]'){
                        count_status++;
                    }
                });

                if(count_status < 1){
                    malert('Phải chọn ít nhất 1 trạng thái','Thông báo');
                    return false;
                }

                ajax_loading(true);
                var url = $(form).attr('action');
                $.post(url, data).done(function(data){
                    ajax_loading(false);
                    if(data.rs == 1)
                    {
                        alert_success(data.msg, function () {  
                            if(data.download)
                                window.location.href = _base_url+'/report/download';
                        });
                    } else {
                        malert(data.msg);
                        if (data.errors) {
                            $.each(data.errors, function (key, msg) {
                                $('#'+key+'-error').html(msg).show();
                            });
                        }
                    }
                });
                return false;
            }

        });
    });
    function set_data_order_status(){
        var countClass = 1;
        var html = '';
        $.each($orderStatus,function(k,v){  
            html = '<input type="checkbox" name="status[]" value="'+v.status+'" ';
            if($.inArray( v.status, $status ) >= 0)
                html += ' checked ';
            html += ' >'+v.order_status_name+'<br>';

            $('.order_status_'+countClass).append(html);
            countClass++;
            if(countClass > 4)
                countClass = 1;
        });
    }
</script>
@endsection