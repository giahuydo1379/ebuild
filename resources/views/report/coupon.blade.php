@extends('layouts.master')
<?php
$ordering_options = \App\Helpers\General::get_ordering_options();
$version_js = \App\Helpers\General::get_version_js();
?>
@section('content')
    <div class="col-md-">
        <section class="section section-homepage">
            <h3 class="title-section">Báo cáo</h3>
            <div class="panel box-panel">
                <div class="home-management" >
                    @include('report.tabs')

                    <div id="tab1" class="tabcontent active" style="display: block;">
                        <div class="create-landingpage CreatePromotion" style="display: block;">
                            <form class="form-create home-manager no-padding" id="form_export" method="post" action="{{ route("report.export") }}">
                               
                                <div class="row">
                                       
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Mã coupon</label>
                                            <input name="code" id="code" class="form-control datepicker" type="text" value="" placeholder="Mã coupon">
                                            <label id="code-error" class="error" for="code" style="display: none;"></label>
                                        </div>
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
        
        $('#form_export').validate({
            ignore: ".ignore",
            rules: {
                code:'required',
            },
            messages: {
                code: 'Nhập mã coupon',
            },
            submitHandler: function(form) {
               
                ajax_loading(true);
                var data = $(form).serializeArray();
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
</script>
@endsection