@extends('layouts.master')

@section('content')
	<div class="col-md-">
        <div class="wrap_view manage_brand">
            <div class="header ">
                <h2 class="title">Tạo đơn vị mới</h2>
            </div>
			<div class="view_detail_brand create_brand" >
                <div class="header-panel">
                    <h3 class="title ">Thông tin chi tiết đơn vị</h3>
                    <div class="wrap_link">
                        <a href="<?=route('units.index')?>"><i class="fa fa-reply" aria-hidden="true"></i><span>Quay lại</span></a>
                    </div>
                </div>
                <form action="<?=route('units.store')?>" class="frm_update" method="post">
	                <div class="col-md-6">
	                	<div class="box box1_edit">
	                		<div class="bottom">
	                            <div class="row">
	                                <div class="col-md-3">
	                                    <label>Tên đơn vị:</label>
	                                </div>
	                                <div class="col-md-9">
	                                    <input type="text" name="name" value="" placeholder="Nhập tên đơn vị">
	                                    <label id="name-error" class="error" for="name" style="display: none;"></label>
	                                </div>
	                            </div>
	                        </div>
	                	</div>
	                </div>
	                <div class="col-md-12">
		                <div class="wrap_btn">
		                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
		                    <a href="<?=route("units.index")?>" class="cancel">Hủy bỏ</a>
	                    <button type="submit" class="btn-save">Lưu</button>
	                </div>
                </div>
            	</form>
            </div>
        </div>
    </div>
@endsection

@section('after_scripts')
<script type="text/javascript">
	$(function(){
		$('.cancel').on('click',function(){
            $(this).closest('form')[0].reset();
        });

        $('.frm_update').validate({
                ignore: ".ignore",
                rules: {
                    name: "required",
                },
                messages: {
                    name: "Nhập tên đơn vị",
                },
                submitHandler: function(form) {
                    // do other things for a valid form
                    var data = $(form).serializeArray();

                    var url = $(form).attr('action');
                    ajax_loading(true);
                    $.post(url, data).done(function(data){
                        ajax_loading(false);
                        if(data.rs == 1)
                        {
                            alert_success(data.msg, function () {
                                location.href = '<?=route("units.index")?>';
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

                    return false;
                }
            });
	});
</script>
@endsection