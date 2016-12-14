<div id="content" class="show-all-content <?php echo $type; ?>">
    <div class="container-fluid">
        <h4 class="pull-left"><?php echo $text_all; ?></h4>
        <div class="show-all-legend pull-right">
            <span class="label label-success"><?php echo $text_applicable; ?></span>
            <span class="label label-info"><?php echo $text_applied; ?></span>
        </div>
        <div style="margin: 10px 0;" class="clearfix"></div>
        <hr />
        <div class="content">
            <?php if ($type == 'tag') { ?>
            <ul class="nav nav-pills" id="show-all-tag-language">
                <?php foreach ($languages as $language) { ?>
                <li><a href="#show-all-tag-language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                <?php } ?>
            </ul>
            <div class="tab-content">
                <?php foreach ($languages as $language) { ?>
                <div class="tab-pane fade in active" id="show-all-tag-language<?php echo $language['language_id']; ?>">
                    <?php foreach ($all as $key => $value) { ?>
                    <?php if (!empty($applied[$language['language_id']]) && in_array($value, $applied[$language['language_id']])) { ?>
                    <a id="<?php echo $type . '-' . $value; ?>" class="bg-info"><?php echo $value; ?> <i class="fa fa-times-circle"></i></a>
                    <?php } else { ?>
                    <a id="<?php echo $type . '-' . $value; ?>" class="bg-success"><?php echo $value; ?></a>
                    <?php } ?>
                    <?php } ?>
                </div>
                <?php } ?>
            </div>
            <?php } else { ?>
            <?php foreach ($all as $item) { ?>
            <?php if (in_array($item[$type . '_id'], $applied)) { ?>
            <a id="<?php echo $type . '-' . $item[$type . '_id']; ?>" class="bg-info"><?php echo $item['name']; ?><i class="fa fa-times-circle"></i></a>
            <?php } else { ?>
            <a id="<?php echo $type . '-' . $item[$type . '_id']; ?>" class="bg-success"><?php echo $item['name']; ?></a>
            <?php } ?>
            <?php } ?>
            <?php } ?>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" id="show-all-close" class="btn btn-default" data-dismiss="modal">Cancel</button>
    <button type="button" onclick="apply();" class="btn btn-primary">Apply changes</button>
</div>
<style type="text/css"><!--
    .show-all-content {
        top: 10px;
    }

    #modal-popup.modal.in {
        overflow: hidden !important;
        overflow-y: hidden !important;
    }
//--></style>
<script type="text/javascript"><!--
    $('#show-all-tag-language a:first').tab('show');
    <?php if ($type == 'manufacturer') { ?>

    $(document).on('click', '.manufacturer .bg-success', function() {
        $('.bg-info i').remove();
        $('.bg-info').addClass('bg-success');
        $('.bg-success').removeClass('bg-info');

        $(this).addClass('bg-info').append('<i class="fa fa-times-circle"></i>');
        $(this).removeClass('bg-success');
    });

    $(document).on('click', 'manufacturer .fa-times-circle', function() {
        $(this).parent().addClass('bg-success').removeClass('bg-info').find('i').remove();
    });

    function apply() {
        if (!$('.bg-info').length) {
            $('input[name=\'manufacturer\']').val('');
            $('input[name=\'manufacturer_id\']').val(0);
        } else {
            $('input[name=\'manufacturer\']').val($('.bg-info').text());
            $('input[name=\'manufacturer_id\']').val(parseInt($('.bg-info').attr('id').replace('<?php echo $type;?>-', '')));
        }

        $('#show-all-close').trigger('click');
    }

    $('.bg-info i').remove();
    $('.bg-info').addClass('bg-success');
    $('.bg-success').removeClass('bg-info');

    $('#manufacturer-' + $('input[name=\'manufacturer_id\']').val()).addClass('bg-info').append('<i class="fa fa-times-circle"></i>');
    $('#manufacturer-' + $('input[name=\'manufacturer_id\']').val()).removeClass('bg-success');
    <?php } elseif ($type == 'category') { ?>

    $(document).on('click', '.category .bg-success', function() {
        $(this).addClass('bg-info').append('<i class="fa fa-times-circle"></i>');
        $(this).removeClass('bg-success');
    });

    $(document).on('click', '.category .fa-times-circle', function() {
        $(this).parent().addClass('bg-success').removeClass('bg-info').find('i').remove();
    });

    function apply() {
        if (!$('.bg-info').length) {
            $('input[name=\'category\']').val('');
            $('#product-category').remove();
        } else {
            var html = '';

            $('.bg-info').each(function( index ) {
                category_id = parseInt($(this).attr('id').replace('<?php echo $type;?>-', ''));

                html += '<div id="product-category' + parseInt($('.bg-info').attr('id').replace('<?php echo $type;?>-', '')) + '">';
                html += '<i class="fa fa-minus-circle"></i> ' + $(this).text();
                html += '<input type="hidden" name="product_category[]" value="' + parseInt($('.bg-info').attr('id').replace('<?php echo $type;?>-', '')) + '" /></div>';
                html += '</div>';
            });

            $('#product-category').html('');
            $('#product-category').append(html);
        }

        $('#show-all-close').trigger('click');
    }

    $('.bg-info i').remove();
    $('.bg-info').addClass('bg-success');
    $('.bg-success').removeClass('bg-info');

    $('div[id^=\'product-category\'] input').each(function( index ) {
        $('#category-' + $(this).val()).addClass('bg-info').append('<i class="fa fa-times-circle"></i>');
        $('#category-' + $(this).val()).removeClass('bg-success');
    });
    <?php } else { ?>

    $(document).on('click', '.tag .bg-success', function() {
        $(this).addClass('bg-info').append('<i class="fa fa-times-circle"></i>');
        $(this).removeClass('bg-success');
    });

    $(document).on('click', '.tag .fa-times-circle', function() {
        $(this).parent().addClass('bg-success').removeClass('bg-info').find('i').remove();
    });

    function apply() {
        $('.show-all-content .tab-content .tab-pane').each(function( index ) {
            language_id = parseInt($(this).attr('id').replace('show-all-tag-language', ''));


            if (!$('#product-tag-' + language_id).length) {
                $('#input-tag' + language_id).after('<div id="product-tag-' + language_id + '" class="well well-sm" style="overflow: auto;"></div>');
            }

            if (!$('#show-all-tag-language' + language_id +' .bg-info').length) {
                $('#tag-language' + language_id + ' input[name=\'tag\']').val('');
                $('#product-tag-' + language_id).remove();
            } else {
                var html = '';

                $('.bg-info').each(function( index ) {
                    tag_id = parseInt($(this).attr('id').replace('<?php echo $type;?>-', ''));

                    html += '<div id="product-tag-' + parseInt($('.bg-info').attr('id').replace('<?php echo $type;?>-', '')) + '">';
                    html += '<i class="fa fa-minus-circle"></i> ' + $(this).text();
                    html += '<input type="hidden" name="product_tag[' + language_id + '][]" value="' + parseInt($('.bg-info').attr('id').replace('<?php echo $type;?>-', '')) + '" /></div>';
                    html += '</div>';
                });

                $('#product-tag-' + language_id).html('');
                $('#product-tag-' + language_id).append(html);
            }
        });

        $('#show-all-close').trigger('click');
    }
    <?php } ?>
//--></script>