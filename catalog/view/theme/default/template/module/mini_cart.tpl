<h3 class="module-title"><span><?php echo $heading_title; ?></span></h3>
<div id="cart-<?php echo $module; ?>" class="module-cart">
    <?php if ($products || $vouchers) { ?>
        <table class="table table-striped">
            <?php foreach ($products as $product) { ?>
            <tr>
                <?php if ($setting['product_image']) { ?>
                <td class="text-center hidden-xs"><?php if ($product['thumb']) { ?>
                    <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-thumbnail" /></a>
                    <?php } ?></td>
                <?php } ?>
                <?php if ($setting['product_name']) { ?>
                <td class="text-left"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                    <?php if ($product['option']) { ?>
                    <?php foreach ($product['option'] as $option) { ?>
                    <br />
                    - <small><?php echo $option['name']; ?> <?php echo $option['value']; ?></small>
                    <?php } ?>
                    <?php } ?>
                    <?php if ($product['recurring']) { ?>
                    <br />
                    - <small><?php echo $text_recurring; ?> <?php echo $product['recurring']; ?></small>
                    <?php } ?></td>
                <?php } ?>
                <?php if ($setting['product_quantity']) { ?>
                <td class="text-right">x <?php echo $product['quantity']; ?></td>
                <?php } ?>
                <?php if ($setting['product_total']) { ?>
                <td class="text-right"><?php echo $product['total']; ?></td>
                <?php } ?>
                <td class="text-center"><button type="button" onclick="cart.remove('<?php echo $product['key']; ?>');" title="<?php echo $button_remove; ?>" class="btn btn-danger btn-xs"><i class="fa fa-times"></i></button></td>
            </tr>
            <?php } ?>
            <?php foreach ($vouchers as $voucher) { ?>
            <tr>
                <?php if ($setting['product_image']) { ?>
                <td class="text-center"></td>
                <?php } ?>
                <?php if ($setting['product_name']) { ?>
                <td class="text-left"><?php echo $voucher['description']; ?></td>
                <?php } ?>
                <?php if ($setting['product_quantity']) { ?>
                <td class="text-right">x&nbsp;1</td>
                <?php } ?>
                <?php if ($setting['product_total']) { ?>
                <td class="text-right"><?php echo $voucher['amount']; ?></td>
                <?php } ?>
                <td class="text-center text-danger"><button type="button" onclick="voucher.remove('<?php echo $voucher['key']; ?>');" title="<?php echo $button_remove; ?>" class="btn btn-danger btn-xs"><i class="fa fa-times"></i></button></td>
            </tr>
            <?php } ?>
        </table>
        <div>
            <?php if ($setting['product_total']) { ?>
            <table class="table table-bordered">
                <?php foreach ($totals as $total) { ?>
                <tr>
                    <td class="text-right"><strong><?php echo $total['title']; ?></strong></td>
                    <td class="text-right"><?php echo $total['text']; ?></td>
                </tr>
                <?php } ?>
            </table>
            <?php } ?>
            <div class="buttons" style="min-height: 15px">
                <?php if ($setting['button_continue']) { ?>
                <div class="pull-left">
                    <a class="button-continue hidden" data-dismiss="modal" aria-hidden="true"><strong><b><i class="fa fa-angle-right"></i></b> <?php echo $button_shopping; ?></strong></a>
                </div>
                <?php } ?>
                <?php if ($setting['button_cart'] || $setting['button_checkout']) { ?>
                <div class="pull-right">
                    <?php if ($setting['button_cart']) { ?>
                    <a class="hidden-xs" href="<?php echo $cart; ?>"><strong><i class="fa fa-shopping-cart"></i> <?php echo $text_cart; ?></strong></a>
                    <?php } ?>
                    &nbsp;&nbsp;&nbsp;
                    <?php if ($setting['button_checkout']) { ?>
                    <a href="<?php echo $checkout; ?>"><strong><i class="fa fa-share"></i> <?php echo $text_checkout; ?></strong></a>
                    <?php } ?>
                </div>
                <?php } ?>
            </div>
        </div>
    <?php } else { ?>
        <p class="text-center"><?php echo $text_empty; ?></p>
    <?php } ?>
    <input type="hidden" value="<?php echo $module_id; ?>" name="module-cart"/>
</div>
