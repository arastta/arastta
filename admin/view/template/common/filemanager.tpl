<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"><?php echo $heading_title; ?></h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-5"><a href="<?php echo $parent; ?>" data-toggle="tooltip" title="<?php echo $button_parent; ?>" id="button-parent" class="btn btn-default"><i class="fa fa-level-up"></i></a> <a href="<?php echo $refresh; ?>" data-toggle="tooltip" title="<?php echo $button_refresh; ?>" id="button-refresh" class="btn btn-default"><i class="fa fa-refresh"></i></a>
                    <button type="button" data-toggle="tooltip" title="<?php echo $button_upload; ?>" id="button-upload" class="btn btn-primary"><i class="fa fa-upload"></i></button>
                    <button type="button" data-toggle="tooltip" title="<?php echo $button_folder; ?>" id="button-folder" class="btn btn-default"><i class="fa fa-folder"></i></button>
                    <button disabled type="button" data-toggle="tooltip" title="<?php echo $button_add_image; ?>" id="button-add" class="btn btn-default"><i class="fa fa-plus text-success"></i></button>
                    <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" id="button-delete" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>
                </div>
                <div class="col-sm-7">
                    <div class="input-group">
                        <input type="text" name="search" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_search; ?>" class="form-control">
                        <span class="input-group-btn">
                            <button type="button" data-toggle="tooltip" title="<?php echo $button_search; ?>" id="button-search" class="btn btn-primary"><i class="fa fa-search"></i></button>
                        </span>
                    </div>
                </div>
            </div>
            <hr />
            <div id="image-multiselect">
            <?php foreach (array_chunk($images, 4) as $image) { ?>
            <div class="row">
                <?php foreach ($image as $image) { ?>
                <div class="col-sm-3 text-center">
                    <?php if ($image['type'] == 'directory') { ?>
                    <div class="text-center"><a href="<?php echo $image['href']; ?>" class="directory" style="vertical-align: middle;"><i class="fa fa-folder fa-5x"></i></a></div>
                    <label>
                        <input type="checkbox" name="path[]" value="<?php echo $image['path']; ?>" />
                        <?php echo $image['name']; ?></label>
                    <?php } ?>
                    <?php if ($image['type'] == 'image') { ?>
                    <a href="<?php echo $image['href']; ?>" data-original-src="<?php echo $image['original']; ?>" class="thumbnail"><img src="<?php echo $image['thumb']; ?>" alt="<?php echo $image['name']; ?>" title="<?php echo $image['name']; ?>" /></a>
                    <label>
                        <input type="checkbox" name="path[]" value="<?php echo $image['path']; ?>" />
                        <?php echo $image['name']; ?></label>
                    <?php } ?>
                </div>
                <?php } ?>
            </div>
            <br />
            <?php } ?>
            </div>
        </div>
        <div class="modal-footer"><?php echo $pagination; ?></div>
    </div>
</div>
<script type="text/javascript"><!--
$('a.thumbnail').on('click', function(e) {
    e.preventDefault();

    <?php if ($thumb) { ?>
    $('#<?php echo $thumb; ?>').find('img').attr('src', $(this).find('img').attr('src'));
    <?php } ?>

    <?php if($customizer) { ?>
    $('#<?php echo $customizer; ?>').attr('src', $(this).find('img').attr('src'));
    <?php } ?>

    <?php if ($target) { ?>
    $('#<?php echo $target; ?>').attr('value', $(this).parent().find('input').attr('value')).trigger('change');
    <?php } else { ?>
    var range, sel = document.getSelection();

    if (sel.rangeCount) {
        var img = document.createElement('img');
        img.src = $(this).attr('href');

        range = sel.getRangeAt(0);
        range.insertNode(img);
    }
    <?php } ?>

    <?php if (empty($thumb) && empty($target) && empty($mode)) { ?>
    if (typeof InsertTinyMCEImage == 'function') {
        InsertTinyMCEImage($(this).attr('href'), $(this).attr('data-original-src'));
    }
    <?php } ?>

    <?php if ($mode == 'basic') { ?>
    var basic  = '<div class="file-preview-frame">';
        basic += '    <img src="' + $(this).find('img').attr('src') + '" class="file-preview-image" title="' + $(this).find('img').attr('src') + '" alt="' + $(this).find('img').attr('src') + '" style="width:auto;height:160px;">';
        basic += '    <div class="file-thumbnail-footer">';
        basic += '        <div class="file-footer-caption" title="' + $(this).find('img').attr('src') + '">' + $(this).parent().find('input').attr('value').split('/').pop() + '</div>';
        basic += '         <div class="file-actions">';
        basic += '            <div class="file-footer-buttons">';
        basic += '                <button type="button" class="kv-file-remove btn btn-xs btn-default" title="Remove file" onclick="removeBasicImage($(this));"><i class="glyphicon glyphicon-trash text-danger"></i></button>';
        basic += '            </div>';
        basic += '            <div class="file-upload-indicator" title=""></div>';
        basic += '            <div class="clearfix"></div>';
        basic += '        </div>';
        basic += '    </div>';
        basic += '    <input type="hidden" name="product_image[' + image_row + '][image]" value="' + $(this).parent().find('input').attr('value') + '" id="input-image' + image_row + '" />';
        basic += '    <input type="hidden" name="product_image[' + image_row + '][sort_order]" value="' + image_sort + '" />';
        basic += '</div>';

        image_row++;
        image_sort++;
    $('.file-preview-thumbnails').append(basic);
    <?php } ?>

    $('#modal-image').modal('hide');
});

$('a.directory').on('click', function(e) {
    e.preventDefault();

    $('#modal-image').load($(this).attr('href'));
});

$('.pagination a').on('click', function(e) {
    e.preventDefault();

    $('#modal-image').load($(this).attr('href'));
});

$('#button-parent').on('click', function(e) {
    e.preventDefault();

    $('#modal-image').load($(this).attr('href'));
});

$('#button-refresh').on('click', function(e) {
    e.preventDefault();

    $('#modal-image').load($(this).attr('href'));
});

$('input[name=\'search\']').on('keydown', function(e) {
    if (e.which == 13) {
        $('#button-search').trigger('click');
    }
});

$('#button-search').on('click', function(e) {
    var url = 'index.php?route=common/filemanager&token=<?php echo $token; ?>&directory=<?php echo $directory; ?>';

    var filter_name = $('input[name=\'search\']').val();

    if (filter_name) {
        url += '&filter_name=' + encodeURIComponent(filter_name);
    }

    <?php if ($thumb) { ?>
    url += '&thumb=' + '<?php echo $thumb; ?>';
    <?php } ?>
    
    <?php if ($target) { ?>
    url += '&target=' + '<?php echo $target; ?>';
    <?php } ?>

    $('#modal-image').load(url);
});
//--></script>
<script type="text/javascript"><!--
$('#button-add').on('click', function() {
    var images = $('#modal-image input[name^=\'path\']:checked').parent().parent().find('.thumbnail').parent().find('input[name^=\'path\']:checked');
    var image_row = parseInt($('input[name^=\'product_image\']').length / 2);

    image_sort = image_row;

    $.each(images, function(key, image) {
        <?php if ($mode == 'basic') { ?>
        var basic  = '<div class="file-preview-frame file-preview-initial ui-draggable ui-draggable-handle">';
            basic += '    <div class="kv-file-content">';
            basic += '        <img src="' + $(this).parent().parent().find('img').attr('src') + '" class="file-preview-image" title="' + $(this).parent().parent().find('img').attr('src') + '" alt="' + $(this).parent().parent().find('img').attr('src') + '" style="width:auto;height:160px;">';
            basic += '    </div>';
            basic += '    <div class="file-thumbnail-footer">';
            basic += '        <div class="file-footer-caption" title="' + $(this).parent().parent().find('img').attr('src') + '">' + $(this).parent().find('input').attr('value').split('/').pop() + '</div>';
            basic += '         <div class="file-actions">';
            basic += '            <div class="file-footer-buttons">';
            basic += '                <button type="button" class="kv-file-remove btn btn-xs btn-default" title="Remove file" onclick="removeBasicImage($(this));"><i class="glyphicon glyphicon-trash text-danger"></i></button>';
            basic += '            </div>';
            basic += '            <span class="file-drag-handle drag-handle-init text-info" title="Move / Rearrange"><i class="glyphicon glyphicon-menu-hamburger"></i></span>';
            basic += '            <div class="file-upload-indicator" title=""></div>';
            basic += '            <div class="clearfix"></div>';
            basic += '        </div>';
            basic += '    </div>';
            basic += '    <input type="hidden" name="product_image[' + image_row + '][image]" value="' + $(this).parent().find('input').attr('value') + '" id="input-image' + image_row + '" />';
            basic += '    <input type="hidden" name="product_image[' + image_row + '][sort_order]" value="' + image_sort + '" />';
            basic += '</div>';

        image_row++;
        image_sort++;

        $('.file-preview-thumbnails').append(basic);
         <?php } else { ?>
            html  = '<tr id="image-row' + image_row + '">';
            html += '  <td class="text-left"><a href="" id="thumb-image' + image_row + '"data-toggle="image" class="img-thumbnail"><img src="' + $(this).parent().parent().find('img').attr('src') + '" alt="' + $(this).parent().parent().find('img').attr('src') + '" title="' + $(this).parent().parent().find('img').attr('src') + '"/></a><input type="hidden" name="product_image[' + image_row + '][image]" value="' + $(this).parent().find('input').attr('value') + '" id="input-image' + image_row + '" /></td>';
            html += '  <td class="text-right"><input type="text" name="product_image[' + image_row + '][sort_order]" value="' + image_sort + '" class="form-control" /></td>';
            html += '  <td class="text-left"><button type="button" onclick="$(\'#image-row' + image_row  + '\').remove();" data-toggle="tooltip" title="Remove" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
            html += '</tr>';

            $('#images tbody').append(html);

            image_sort++;
            image_row++;
        <?php } ?>
    });

    $('#modal-image').modal('hide');
});

$('#button-upload').on('click', function() {
    $('#form-upload').remove();

    $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file[]" value="" multiple=true /></form>');

    $('#form-upload input[name=\'file[]\']').trigger('click');

    if (typeof timer != 'undefined') {
        clearInterval(timer);
    }

    timer = setInterval(function() {
        if ($('#form-upload input[name=\'file[]\']').val() != '') {
            clearInterval(timer);

            $.ajax({
                url: 'index.php?route=common/filemanager/upload&token=<?php echo $token; ?>&directory=<?php echo $directory; ?>',
                type: 'post',
                dataType: 'json',
                data: new FormData($('#form-upload')[0]),
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#button-upload i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
                    $('#button-upload').prop('disabled', true);
                },
                complete: function() {
                    $('#button-upload i').replaceWith('<i class="fa fa-upload"></i>');
                    $('#button-upload').prop('disabled', false);
                },
                success: function(json) {
                    if (json['error']) {
                        alert(json['error']);
                    }

                    if (json['success']) {
                        alert(json['success']);

                        $('#button-refresh').trigger('click');
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        }
    }, 500);
});

$('#button-folder').popover({
    html: true,
    placement: 'bottom',
    trigger: 'click',
    title: '<?php echo $entry_folder; ?>',
    content: function() {
        html  = '<div class="input-group">';
        html += '  <input type="text" name="folder" value="" placeholder="<?php echo $entry_folder; ?>" class="form-control">';
        html += '  <span class="input-group-btn"><button type="button" title="<?php echo $button_folder; ?>" id="button-create" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></span>';
        html += '</div>';

        return html;
    }
});

$('#button-folder').on('shown.bs.popover', function() {
    $('#button-create').on('click', function() {
        $.ajax({
            url: 'index.php?route=common/filemanager/folder&token=<?php echo $token; ?>&directory=<?php echo $directory; ?>',
            type: 'post',
            dataType: 'json',
            data: 'folder=' + encodeURIComponent($('input[name=\'folder\']').val()),
            beforeSend: function() {
                $('#button-create').prop('disabled', true);
            },
            complete: function() {
                $('#button-create').prop('disabled', false);
            },
            success: function(json) {
                if (json['error']) {
                    alert(json['error']);
                }

                if (json['success']) {
                    alert(json['success']);

                    $('#button-refresh').trigger('click');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });
});

$('#modal-image #button-delete').on('click', function(e) {
    if (confirm('<?php echo $text_confirm; ?>')) {
        $.ajax({
            url: 'index.php?route=common/filemanager/delete&token=<?php echo $token; ?>',
            type: 'post',
            dataType: 'json',
            data: $('input[name^=\'path\']:checked'),
            beforeSend: function() {
                $('#button-delete').prop('disabled', true);
            },
            complete: function() {
                $('#button-delete').prop('disabled', false);
            },
            success: function(json) {
                if (json['error']) {
                    alert(json['error']);
                }

                if (json['success']) {
                    alert(json['success']);

                    $('#button-refresh').trigger('click');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }
});

<?php if (!empty($mode) || ((strpos($data['target'], 'input-image') === false) || (strpos($data['target'], 'input-image') === false))) { ?>
$('#button-add').prop('disabled', false);

$('#image-multiselect').on('click', function(e) {
    fakePOS = false;

    $('.row').removeClass('cs-selected');
    $('.col-sm-3.text-center').removeClass('cs-selected');
    $('.col-sm-3.text-center input').prop('checked', false);

    $('#image-multiselect input').blur();
});

$('#image-multiselect label').on('click', function(e) {
    e.stopImmediatePropagation();
    $(this).parent().addClass('cs-selected');
    $(this).children('input').prop('checked', true);

    fakePOS = true;
});

$('input[name^=\'path\']').on('click', function(e) {
    e.stopImmediatePropagation();
    $(this).parent().parent().addClass('cs-selected');
    $(this).prop('checked', true);

    fakePOS = true;
});

$('#image-multiselect').csSelectable({
    items         : '#image-multiselect .col-sm-3.text-center',
    selectionClass: 'cs-selection-box',
    selectedClass : 'cs-selected',
    onSelect  : function (element) {
        $(element).find('input').prop('checked', true);
    },
    onUnSelect: function (element) {
        $(element).find('input').prop('checked', false);
    },
    onClear   : function () {
        $('#image-multiselect').find('input').prop('checked', false);
    },
    autoRefresh   : true
});
<?php } ?>
//--></script>
