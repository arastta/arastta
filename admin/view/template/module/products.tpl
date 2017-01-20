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
    $(document).ready(function() {
        if ($('#input-type').val() == 'featured') {
            // Category
            $('input[name=\'category\']').parent().parent().hide();
        }

        if ($('#input-type').val() != 'featured') {
            // Product
            $('input[name=\'product\']').parent().parent().hide();
            $('input[name=\'random_product\']').parent().parent().parent().parent().hide();
        }
    });

    $(document).on('change', 'select[name=\'type\']', function() {
        // Category
        $('input[name=\'category\']').parent().parent().show();

        // Product
        $('input[name=\'product\']').parent().parent().hide();
        $('input[name=\'random_product\']').parent().parent().parent().parent().hide();

        if ($(this).val() == 'featured') {
            // Category
            $('input[name=\'category\']').parent().parent().hide();

            // Product
            $('input[name=\'product\']').parent().parent().show();
            $('input[name=\'random_product\']').parent().parent().parent().parent().show();
        }
    });

    // Category
    $('input[name=\'category\']').autocomplete({
        'source': function(request, response) {
            $.ajax({
                url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                dataType: 'json',
                success: function(json) {
                    response($.map(json, function(item) {
                        return {
                            label: item['name'],
                            value: item['category_id']
                        }
                    }));
                }
            });
        },
        'select': function(item) {
            $('input[name=\'category\']').val('');

            $('#categories' + item['value']).remove();

            $('#categories').append('<div id="categories' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="category[]" value="' + item['value'] + '" /></div>');
        }
    });

    $('#categories').delegate('.fa-minus-circle', 'click', function() {
        $(this).parent().remove();
    });

    // Product
    $('input[name=\'product\']').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                dataType: 'json',
                success: function(json) {
                    if (json.length === 0) {
                        $.ajax({
                            url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_model=' +  encodeURIComponent(request),
                            dataType: 'json',
                            success: function(json) {
                                response($.map(json, function(item) {
                                    return {
                                        label: item['name'],
                                        value: item['product_id']
                                    }
                                }));
                            }
                        });
                    } else {
                        response($.map(json, function(item) {
                            return {
                                label: item['name'],
                                value: item['product_id']
                            }
                        }));
                    }
                }
            });
        },
        select: function(item) {
            $('input[name=\'product\']').val('');

            $('#featured-product' + item['value']).remove();

            $('#featured-product').append('<div id="featured-product' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product[]" value="' + item['value'] + '" /></div>');
        }
    });

    $('#featured-product').delegate('.fa-minus-circle', 'click', function() {
        $(this).parent().remove();
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
