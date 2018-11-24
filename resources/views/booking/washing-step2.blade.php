@extends('layouts.master')

@section('content')
<!--page-container-->
<div class="col-md-">
    <div class="wrap_view view_product create_product">
        <div class="header">
            <h2 class="title"><?=isset($object['id'])?'Cập nhật đơn hàng: '.$object['service_name'].' - ['.$object['code'].']'
                    :'Thêm mới đơn hàng - '.$service['name']?></h2>
            <a href="<?=url()->previous();?>"><i class="fa fa-reply" aria-hidden="true"></i>  Quay lại</a>
        </div>
    </div>

    <section class="clean-house form">
        <div class="wrap-form">
            @if(!empty($washing_dry[$washing_dry_id]))
            <div class="tab">
                @foreach($washing_dry[$washing_dry_id] as $item)
                @if(!empty($washing_dry[$item['id']]))
                <button class="tablinks" onclick="openCity(event, <?=$item['id']?>)"><?=$item['name']?></button>
                @endif
                @endforeach
            </div>
            @foreach($washing_dry[$washing_dry_id] as $key => $value)
            <div id="<?=$value['id']?>" class="tabcontent <?=$key == 0?'active':''?>">
                @if(!empty($washing_dry[$value['id']]))
                <ul class="list-pick">
                    @foreach($washing_dry[$value['id']] as $item)
                    <li>
                        <div class="col-xs-4 no-padding-left">
                            <img src="<?=$item['image_url'].$item['image_location']?>" alt="">
                        </div>
                        <div class="col-xs-5 no-padding">
                            <p><b><?=$item['name']?></b></p>
                            <p><?=$item['price']?> VNĐ</p>
                        </div>
                        <div class="col-xs-3 no-padding ip_">
                            <input class="amount" id="amount_<?=$item['id']?>" type="number" value="0" data-name="<?=$item['name']?>" data-id="<?=$item['services_units_id']?>" data-price="<?=$item['price']?>">
                            <div class="arrow">
                                <i class="fa fa-sort-up"></i>
                                <i class="fa fa-sort-down"></i>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @endif
            </div>
            @endforeach
            @endif
            <div class="wrap-total-price">
                <div class="total washing_selected">                    
                </div>
                <a href="javascript:void(0)" class="btn btn-primary btn-next btn_submit">Tiếp theo <i class="fa fa-arrow-right"></i></a>
            </div>
        </div>
    </section>
</div>
@stop

@section('after_scripts')
    <script type="text/javascript" src="/js/jquery.validate.min.js"></script>
    <script type="text/javascript">     
        var url = '<?=$url?>';   
        $(function(){
            var data_step2 = localStorage.getItem("washing_data_step2");
            if(data_step2){
                var washing_dry = JSON.parse(data_step2);                
                setHtmlWashingSelected(washing_dry);
            }
            $(document).on('change','.amount',function(){
                if($(this).val() < 0)
                    $(this).val(0);

                var washing_dry = getAllWashing();
                setHtmlWashingSelected(washing_dry);
            });

            $(document).on('click','.btn_submit',function(){
                var data = getAllWashing();                
                localStorage.setItem("washing_data_step2", JSON.stringify(data));
                window.location.href = url;
            });

            $(document).on('click','i.back',function(){
                window.location.href = url;
            });
        });
        function setHtmlWashingSelected(washing_dry){            
            var html = '';
            $.each(washing_dry,function(k,v){
                html += '<p>'+v.name+' <span class="color">x'+v.amount+'</span> <span class="price pull-right">'+numeral(v.amount * v.price).format('0,0')+'VNĐ</span></p>';
                $('#amount_'+v.services_units_id).val(v.amount);
            });
            $('.washing_selected').html(html);
        }
        function getAllWashing(){
            var washing_dry = [];
            $('.amount').each(function(){
                if($(this).val() > 0){
                    tmp = {
                        "services_units_id":$(this).data('id'),
                        "name":$(this).data('name'),
                        "amount":parseInt($(this).val()),
                        "price":$(this).data('price')
                    };
                    washing_dry.push(tmp);
                }
            });
            return washing_dry;
        }
    </script>
@stop