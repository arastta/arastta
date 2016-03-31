<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" onclick="save('save')" form="form-cart" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success" data-original-title="Save"><i class="fa fa-check"></i></button>
                <button type="submit" form="form-cart" data-toggle="tooltip" title="<?php echo $button_saveclose; ?>" class="btn btn-default" data-original-title="Save & Close"><i class="fa fa-save text-success"></i></button>
                <button type="submit" onclick="save('new')" form="form-cart" data-toggle="tooltip" title="<?php echo $button_savenew; ?>" class="btn btn-default" data-original-title="Save & New"><i class="fa fa-plus text-success"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-times-circle text-danger"></i></a>
            </div>
            <h1><?php echo $heading_title; ?></h1>
        </div>
    </div>
    <div class="container-fluid">
        <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
            </div>
            <div class="panel-body">
                <?php  echo $form_fields; ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript"><!--
var popup = '<?php echo $popup; ?>';
$( document ).ready(function() {
    $('#popup1').parent().parent().parent().addClass('popup-cart');

    if ($('#input-theme').val() == 'mini_cart') {
        $('input[name=\'product_model\']').parent().parent().parent().parent().hide();
        $('input[name=\'product_price\']').parent().parent().parent().parent().hide();
        $('input[name=\'coupon\']').parent().parent().parent().parent().hide();
    }
});

$(document).on('change', 'input[name=\'popup\']', function() {
    if (popup == $(this).val()) {
        return;
    }

    popup = $(this).val();

    $('.popup-cart-message').remove();
    $('.page-header .pull-right button').prop('disabled', false);

    if (popup == 1) {
        $.ajax({
            url: 'index.php?route=module/cart/popup&token=<?php echo $token; ?>',
            type: 'post',
            data: {popup : popup, module_id : getURLVar('module_id')},
            dataType: 'json',
            beforeSend: function() {
                $('.popup-cart-message').remove();

                $('.col-sm-10.popup-cart').append('<label class="popup-cart-spin" style="margin-left: 15px"><i class="fa fa-spinner fa-spin checkout-spin"></i></label>');
            },
            complete : function() {
                $('.popup-cart-spin').remove();
            },
            success: function(json) {
                if (json['warning']) {
                    $('.col-sm-10.popup-cart').append('<label class="text-danger popup-cart-message" style="margin-left: 15px">' + json['warning'] + '</label>');

                    $('.page-header .pull-right button').prop('disabled', true);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }
});

var theme = '<?php echo $theme; ?>';

$(document).on('change', '#input-theme', function() {
    if (theme == $(this).val()) {
        return;
    }

    theme = $(this).val();

    if (theme == 'cart') {
        $('input[name=\'product_model\']').parent().parent().parent().parent().show();
        $('input[name=\'product_price\']').parent().parent().parent().parent().show();
        $('input[name=\'coupon\']').parent().parent().parent().parent().show();
    } else {
        $('input[name=\'product_model\']').parent().parent().parent().parent().hide();
        $('input[name=\'product_price\']').parent().parent().parent().parent().hide();
        $('input[name=\'coupon\']').parent().parent().parent().parent().hide();
    }
});
//--></script>
<script type="text/javascript"><!--
function save(type){
    var input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'button';
    input.value = type;
    form = $("form[id^='form-']").append(input);
    form.submit();
}
//--></script>
<?php echo $footer; ?>
