<h3 class="module-title"><span><?php echo $heading_title; ?></span></h3>
<div id="cart-<?php echo $module; ?>" class="module-cart">
    <?php if ($attention) { ?>
    <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $attention; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="row">
        <div class="col-sm-12">
        <?php if ($products || $vouchers) { ?>
            <?php if ($setting['product_image'] || $setting['product_name'] || $setting['product_model'] || $setting['product_quantity'] || $setting['product_price'] || $setting['product_total']) { ?>
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <?php if ($setting['product_image']) { ?>
                            <td class="text-center"><?php echo $column_image; ?></td>
                            <?php } ?>
                            <?php if ($setting['product_name']) { ?>
                            <td class="text-left"><?php echo $column_name; ?></td>
                            <?php } ?>
                            <?php if ($setting['product_model']) { ?>
                            <td class="text-left"><?php echo $column_model; ?></td>
                            <?php } ?>
                            <?php if ($setting['product_quantity']) { ?>
                            <td class="text-left"><?php echo $column_quantity; ?></td>
                            <?php } ?>
                            <?php if ($setting['product_price']) { ?>
                            <td class="text-right"><?php echo $column_price; ?></td>
                            <?php } ?>
                            <?php if ($setting['product_total']) { ?>
                            <td class="text-right"><?php echo $column_total; ?></td>
                            <?php } ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($products as $product) { ?>
                        <tr>
                            <?php if ($setting['product_image']) { ?>
                            <td class="text-center"><?php if ($product['thumb']) { ?>
                                <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-thumbnail" /></a>
                                <?php } ?></td>
                            <?php } ?>
                            <?php if ($setting['product_name']) { ?>
                            <td class="text-left"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                                <?php if (!$product['stock']) { ?>
                                <span class="text-danger">***</span>
                                <?php } ?>
                                <?php if ($product['option']) { ?>
                                <?php foreach ($product['option'] as $option) { ?>
                                <br />
                                <small><?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                                <?php } ?>
                                <?php } ?>
                                <?php if ($product['reward']) { ?>
                                <br />
                                <small><?php echo $product['reward']; ?></small>
                                <?php } ?>
                                <?php if ($product['recurring']) { ?>
                                <br />
                                <span class="label label-info"><?php echo $text_recurring_item; ?></span> <small><?php echo $product['recurring']; ?></small>
                                <?php } ?></td>
                            <?php } ?>
                            <?php if ($setting['product_model']) { ?>
                            <td class="text-left"><?php echo $product['model']; ?></td>
                            <?php } ?>
                            <?php if ($setting['product_quantity']) { ?>
                            <td class="text-left"><div class="input-group btn-block" style="max-width: 200px;">
                                <input type="text" name="quantity[<?php echo $product['key']; ?>]" value="<?php echo $product['quantity']; ?>" size="1" class="form-control" />
                            <span class="input-group-btn">
                            <button type="button" data-toggle="tooltip" title="<?php echo $button_update; ?>" class="btn btn-primary" onclick="cart.update('<?php echo $product['key']; ?>', $(this).parent().parent().find('input').val());"><i class="fa fa-refresh"></i></button>
                            <button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger" onclick="cart.remove('<?php echo $product['key']; ?>');"><i class="fa fa-times-circle"></i></button></span></div></td>
                            <?php } ?>
                            <?php if ($setting['product_price']) { ?>
                            <td class="text-right"><?php echo $product['price']; ?></td>
                            <?php } ?>
                            <?php if ($setting['product_total']) { ?>
                            <td class="text-right"><?php echo $product['total']; ?></td>
                            <?php } ?>
                        </tr>
                        <?php } ?>
                        <?php foreach ($vouchers as $vouchers) { ?>
                        <tr>
                            <?php if ($setting['product_image']) { ?>
                            <td></td>
                            <?php } ?>
                            <?php if ($setting['product_name']) { ?>
                            <td class="text-left"><?php echo $vouchers['description']; ?></td>
                            <?php } ?>
                            <?php if ($setting['product_model']) { ?>
                            <td class="text-left"></td>
                            <?php } ?>
                            <?php if ($setting['product_quantity']) { ?>
                            <td class="text-left"><div class="input-group btn-block" style="max-width: 200px;">
                                <input type="text" name="" value="1" size="1" disabled="disabled" class="form-control" />
                                <span class="input-group-btn"><button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger" onclick="voucher.remove('<?php echo $vouchers['key']; ?>');"><i class="fa fa-times-circle"></i></button></span></div></td>
                            <?php } ?>
                            <?php if ($setting['product_price']) { ?>
                            <td class="text-right"><?php echo $vouchers['amount']; ?></td>
                            <?php } ?>
                            <?php if ($setting['product_total']) { ?>
                            <td class="text-right"><?php echo $vouchers['amount']; ?></td>
                            <?php } ?>
                        </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </form>
            <?php } ?>
            <?php if ($coupon && $setting['coupon']) { ?>
            <h2><?php echo $text_next; ?></h2>
            <p><?php echo $text_next_choice; ?></p>
            <div class="panel-group" id="accordion-<?php echo $module_id; ?>"><?php echo $coupon; ?></div>
            <?php } ?>
            <br />
            <?php if ($setting['product_total']) { ?>
            <div class="row">
                <div class="col-sm-4 col-sm-offset-8">
                    <table class="table table-bordered">
                        <?php foreach ($totals as $total) { ?>
                        <tr>
                            <td class="text-right"><strong><?php echo $total['title']; ?>:</strong></td>
                            <td class="text-right"><?php echo $total['text']; ?></td>
                        </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
            <?php } ?>
            <div class="buttons" style="min-height: 25px">
                <?php if ($setting['button_continue']) { ?>
                <div class="pull-left"><a class="btn btn-default button-continue hidden" data-dismiss="modal" aria-hidden="true"><?php echo $button_shopping; ?></a></div>
                <?php } ?>
                <?php if ($setting['button_cart'] || $setting['button_checkout']) { ?>
                <div class="pull-right">
                    <?php if ($setting['button_cart']) { ?>
                    <a href="<?php echo $cart; ?>" class="btn btn-primary hidden-xs"><i class="fa fa-shopping-cart"></i> <?php echo $text_cart; ?></a>
                    <?php } ?>
                    &nbsp;&nbsp;&nbsp;
                    <?php if ($setting['button_checkout']) { ?>
                    <a href="<?php echo $checkout; ?>" class="btn btn-primary"><i class="fa fa-share"></i> <?php echo $text_checkout; ?></a>
                    <?php } ?>
                </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <p class="text-center"><?php echo $text_empty; ?></p>
        <?php } ?>
        </div>
        <input type="hidden" value="<?php echo $module_id; ?>" name="module-cart"/>
    </div>
</div>
<script type="text/javascript"><!--
$(document).on('click', '#<?php echo $coupon_button; ?>', function(e) {
    e.preventDefault();

    $.ajax({
        url: 'index.php?route=checkout/coupon/coupon',
        type: 'post',
        data: 'coupon=' + encodeURIComponent($(this).parent().parent().find('input[name=\'coupon\']').val()),
        dataType: 'json',
        beforeSend: function() {
            $('#<?php echo $coupon_button; ?>').button('loading');
        },
        complete: function() {
            $('#<?php echo $coupon_button; ?>').button('reset');
        },
        success: function(json) {
            $('.alert').remove();

            if (json['error']) {
                $('.module-cart .row').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
            }

            if (json['redirect']) {
                $('.tooltip.fade.top.in').remove();

                $('#cart > ul').load('index.php?route=common/cart/info ul li');

                $.each($('.module-cart'), function(i, module) {
                    cart_id = $(module).attr('id');
                    module_cart_path = '&module_id=' + $(module).find('input[name="module-cart"]').val() + '&module=' + $(module).attr('id').replace('cart-', '');

                    $('#' + cart_id).load('index.php?route=module/cart/info' + module_cart_path + ' .module-cart >');
                });
            }
        }
    });
});
//--></script>