@foreach($promotions as $item)
    <?php
    switch($item['package_id']){
        case 1:    
    ?>      
            <div class="row gift">
                <p><label for=""><i class="fa fa-gift" aria-hidden="true"></i> </label>Được tặng một sản phẩm cùng loại
                </p>
            </div>
    <?php
        break;

        case 2:
        case 7:
    ?>
            <?php
            $pid = $item['gift_products'][0];
            if (isset($products[$pid])) {
            ?>
            <div class="row gift">
                <p><label for=""><i class="fa fa-gift" aria-hidden="true"></i> Sản phẩm được tặng kèm:</label>
                <?=$products[$pid]['name']?>
                    @if ($products[$pid]['price'])
                        ( <span class="price"><?= number_format($products[$pid]['price']) ?><sup>đ</sup></span> )
                    @endif
                </p>
            </div>
            <?php } ?>
    <?php        
        break;

        case 4:
    ?>
        <?php
        $giff = [];
        foreach ($item['gift_products'] as $pid) {
            if (isset($products[$pid])) {
            $giff[] = $products[$pid]['name'].($products[$pid]['price'] ? '( <span class="price">'.number_format($products[$pid]['price']).'<sup>đ</sup></span> )' : '');
            }
        }
        if (count($giff) > 0) {
        ?>
            <div class="row gift">
                <p><label for=""><i class="fa fa-gift" aria-hidden="true"></i> Sản phẩm được tặng kèm:</label>
                <?=implode(', và ', $giff)?></p>
            </div>
        <?php } ?>
    <?php
        break;
    }
    ?>
@endforeach