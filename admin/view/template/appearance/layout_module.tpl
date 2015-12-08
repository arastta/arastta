<?php echo $header; ?><?php echo $column_left; ?>
<div class="row layout-builder" id="layout-builder">
    <div class="col-md-4 col-sm-5">
        <div class="left-module-block">
            <div id="module_list" class="module_list" data-text-confirm="<?php echo $text_confirm;?>" data-text-edit="<?php echo $text_edit;?>">
                <div class="heading-accordion"><?php echo $text_installed_module; ?></div>
                <div class="module_accordion accordion">
                    <?php foreach($extensions as $modules) { ?>
                    <div class="accordion-heading"><i class="fa fa-cubes"></i><span class="module-name"><?php echo $modules['name']; ?></span>
                        <div class="btn-group">
                            <?php /*if(!empty($modules['module'])) { ?>
                            <!--<a href="<?php echo $modules['link']; ?>" data-type="iframe"  data-toggle="tooltip" title="<?php echo $button_install; ?>" class="btn btn-success btn-edit"><i class="fa fa-plus-circle"></i></a>-->
                            <?php }*/ ?>
                        </div>
                    </div>
                    <?php if(!empty($modules['module'])) { ?>
                    <?php foreach($modules['module'] as $module) { ?>
                    <div class="accordion-content accordion-content-drag">
                        <div class="module-block ui-draggable" data-code="<?php echo $module['code']; ?>" style="cursor: default;" id="<?php echo str_replace('.', '_', $module['code']); ?>"><i class="fa fa-arrows-alt"></i><span class="module-name"><?php echo $module['name']; ?></span>
                            <?php /* ?>
                            <a href="<?php echo $modules['link'] . '&module_id=' . $module['module_id']; ?>" data-type="iframe" data-toggle="tooltip" style="top:6px!important;font-size:1.2em !important;right: 35px;" title="<?php echo $button_edit; ?>" class="btn btn-primary btn-xs btn-edit btn-group"><i class="fa fa-pencil"></i></a>
                            <a onclick="removeModule('<?php echo $module['module_id']; ?>', '<?php echo str_replace('.', '_', $module['code']); ?>');" data-toggle="tooltip" name="reset" style="top:6px!important;font-size:1.2em !important;" title="<?php echo $button_remove; ?>" id="reset<?php echo $module['module_id']; ?>" class="btn btn-danger btn-xs reset" title="<?php echo $button_remove; ?>"><i class="fa fa-trash-o"></i></a>
                            <?php */ ?>
                        </div>
                    </div>
                    <?php } ?>
                    <?php } else { ?>
                    <div class="accordion-content accordion-content-drag">
                        <div class="module-block ui-draggable" data-code="<?php echo $modules['code']; ?>" style="cursor: default;"><i class="fa fa-arrows-alt"></i><span class="module-name"><?php echo $modules['name']; ?></span>
                            <?php /* ?>
                            <a href="<?php echo $modules['link']; ?>" data-type="iframe" data-toggle="tooltip" style="top:6px!important;font-size:1.2em !important;" title="<?php echo $button_edit; ?>" class="btn btn-primary btn-xs btn-edit btn-group"><i class="fa fa-pencil"></i></a>
                            <?php */ ?>
                        </div>
                    </div>
                    <?php } ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $footer; ?>
<script type="text/javascript">
    $(document).ready(function() {
        Layout['handleAccordion']();
    });

    $(document).on('click', '.module-block', function() {
        var token = getURLVar('token');
        var array = "<?php echo $layout_position; ?>";
        var modulex = $(this).data('code');
        var ext = $(this)['find']('a')['attr']('href');
        var moduleInstance = $(this).children(".module-name").text();

        $.ajax({
            url: 'index.php?route=appearance/layout/saveModule&token=' + token,
            type: 'post',
            data: {layout_id: '<?php echo $layout_id;?>', layout_position: '<?php echo $layout_position;?>', module_code: modulex, token:token},
            dataType: 'json',
            cache: false,
            success: function(json) {
                if (json['success'] == 1) {
                    $('#res-module-modal').modal('hide');
                    window.location.href = json['link'].replace('&amp;','&');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    function removeModule(module_id, id) {
        $.ajax({
            url: 'index.php?route=appearance/layout/removeModule&token=<?php echo $token;?>',
            type: 'post',
            data: {module_id: module_id},
            dataType: 'json',
            cache: false,
            success: function(json) {
                if (json['success'] == 1) {
                    $('#' + id).remove();
                } else {
                    alert(json['error_warning']);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }
</script>
