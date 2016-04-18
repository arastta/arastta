<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <a href="<?php echo $back; ?>" data-toggle="tooltip" title="<?php echo $button_back; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
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
        <?php if ($success) { ?>
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <div class="row">
            <div class="left-col col-sm-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?php echo $tab_export; ?></h3>
                        <div class="pull-right">
                            <div class="panel-chevron"><i class="fa fa-chevron-up rotate-reset"></i></div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="general">
                            <form action="<?php echo $export; ?>" method="post" enctype="multipart/form-data" id="export" class="form-horizontal">
                                <table class="form">
                                    <tr>
                                        <td><?php echo $entry_export; ?></td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align:top;">
                                            <?php echo $entry_export_type; ?><br />
                                            <?php if ($export_type=='c') { ?>
                                            <input type="radio" name="export_type" value="c" checked="checked" />
                                            <?php } else { ?>
                                            <input type="radio" name="export_type" value="c" />
                                            <?php } ?>
                                            <?php echo $text_export_type_category; ?>
                                            <br />
                                            <?php if ($export_type=='p') { ?>
                                            <input type="radio" name="export_type" value="p" checked="checked" />
                                            <?php } else { ?>
                                            <input type="radio" name="export_type" value="p" />
                                            <?php } ?>
                                            <?php echo $text_export_type_product; ?>
                                            <br />
                                            <?php if ($export_type=='o') { ?>
                                            <input type="radio" name="export_type" value="o" checked="checked" />
                                            <?php } else { ?>
                                            <input type="radio" name="export_type" value="o" />
                                            <?php } ?>
                                            <?php echo $text_export_type_option; ?>
                                            <br />
                                            <?php if ($export_type=='a') { ?>
                                            <input type="radio" name="export_type" value="a" checked="checked" />
                                            <?php } else { ?>
                                            <input type="radio" name="export_type" value="a" />
                                            <?php } ?>
                                            <?php echo $text_export_type_attribute; ?>
                                            <br />
                                            <?php if ($exist_filter) { ?>
                                            <?php if ($export_type=='f') { ?>
                                            <input type="radio" name="export_type" value="f" checked="checked" />
                                            <?php } else { ?>
                                            <input type="radio" name="export_type" value="f" />
                                            <?php } ?>
                                            <?php echo $text_export_type_filter; ?>
                                            <br />
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <tr id="range_type">
                                        <td style="vertical-align:top;"><?php echo $entry_range_type; ?><span class="help"><?php echo $help_range_type; ?></span><br />
                                            <input type="radio" name="range_type" value="id" id="range_type_id"><?php echo $button_export_id; ?> &nbsp;&nbsp;
                                            <input type="radio" name="range_type" value="page" id="range_type_page"><?php echo $button_export_page; ?>
                                            <br /><br />
                                            <span class="id"><?php echo $entry_start_id; ?></span>
                                            <span class="page"><?php echo $entry_start_index; ?></span>
                                            <br />
                                            <input type="text" name="min" value="<?php echo $min; ?>" />
                                            <br />
                                            <span class="id"><?php echo $entry_end_id; ?></span>
                                            <span class="page"><?php echo $entry_end_index; ?></span>
                                            <br />
                                            <input type="text" name="max" value="<?php echo $max; ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="buttons"><a onclick="downloadData();" class="btn btn-primary"><span><?php echo $button_export; ?></span></a></td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?php echo $tab_import; ?></h3>
                        <div class="pull-right">
                            <div class="panel-chevron"><i class="fa fa-chevron-up rotate-reset"></i></div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="import">
                            <form action="<?php echo $import; ?>" method="post" enctype="multipart/form-data" id="import" class="form-horizontal">
                                <table class="form">
                                    <tr>
                                        <td>
                                            <?php echo $entry_import; ?>
                                            <span class="help"><?php echo $help_import; ?></span>
                                            <span class="help"><?php echo $help_format; ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php echo $entry_incremental; ?><br />
                                            <?php if ($incremental) { ?>
                                            <input type="radio" name="incremental" value="1" checked="checked" />
                                            <?php echo $text_yes; ?> <?php echo $help_incremental_yes; ?>
                                            <br />
                                            <input type="radio" name="incremental" value="0" />
                                            <?php echo $text_no; ?> <?php echo $help_incremental_no; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="incremental" value="1" />
                                            <?php echo $text_yes; ?> <?php echo $help_incremental_yes; ?>
                                            <br />
                                            <input type="radio" name="incremental" value="0" checked="checked" />
                                            <?php echo $text_no; ?> <?php echo $help_incremental_no; ?>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $entry_upload; ?><br /><br /><input type="file" name="upload" id="upload" /></td>
                                    </tr>
                                    <tr>
                                        <td class="buttons"><a onclick="uploadData();" class="btn btn-primary"><span><?php echo $button_import; ?></span></a></td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="right-col col-sm-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?php echo $tab_settings; ?></h3>
                        <div class="pull-right">
                            <div class="panel-chevron"><i class="fa fa-chevron-up rotate-reset"></i></div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="setting">
                            <form action="<?php echo $settings; ?>" method="post" enctype="multipart/form-data" id="settings" class="form-horizontal">
                                <table class="form">
                                    <tr>
                                        <td>
                                            <label>
                                                <?php if ($settings_use_option_id) { ?>
                                                <input type="checkbox" name="export_import_settings_use_option_id" value="1" checked="checked" /> <?php echo $entry_settings_use_option_id; ?>
                                                <?php } else { ?>
                                                <input type="checkbox" name="export_import_settings_use_option_id" value="1" /> <?php echo $entry_settings_use_option_id; ?>
                                                <?php } ?>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>
                                                <?php if ($settings_use_option_value_id) { ?>
                                                <input type="checkbox" name="export_import_settings_use_option_value_id" value="1" checked="checked" /> <?php echo $entry_settings_use_option_value_id; ?>
                                                <?php } else { ?>
                                                <input type="checkbox" name="export_import_settings_use_option_value_id" value="1" /> <?php echo $entry_settings_use_option_value_id; ?>
                                                <?php } ?>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>
                                                <?php if ($settings_use_attribute_group_id) { ?>
                                                <input type="checkbox" name="export_import_settings_use_attribute_group_id" value="1" checked="checked" /> <?php echo $entry_settings_use_attribute_group_id; ?>
                                                <?php } else { ?>
                                                <input type="checkbox" name="export_import_settings_use_attribute_group_id" value="1" /> <?php echo $entry_settings_use_attribute_group_id; ?>
                                                <?php } ?>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>
                                                <?php if ($settings_use_attribute_id) { ?>
                                                <input type="checkbox" name="export_import_settings_use_attribute_id" value="1" checked="checked" /> <?php echo $entry_settings_use_attribute_id; ?>
                                                <?php } else { ?>
                                                <input type="checkbox" name="export_import_settings_use_attribute_id" value="1" /> <?php echo $entry_settings_use_attribute_id; ?>
                                                <?php } ?>
                                            </label>
                                        </td>
                                    </tr>
                                    <?php if ($exist_filter) { ?>
                                    <tr>
                                        <td>
                                            <label>
                                                <?php if ($settings_use_filter_group_id) { ?>
                                                <input type="checkbox" name="export_import_settings_use_filter_group_id" value="1" checked="checked" /> <?php echo $entry_settings_use_filter_group_id; ?>
                                                <?php } else { ?>
                                                <input type="checkbox" name="export_import_settings_use_filter_group_id" value="1" /> <?php echo $entry_settings_use_filter_group_id; ?>
                                                <?php } ?>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>
                                                <?php if ($settings_use_filter_id) { ?>
                                                <input type="checkbox" name="export_import_settings_use_filter_id" value="1" checked="checked" /> <?php echo $entry_settings_use_filter_id; ?>
                                                <?php } else { ?>
                                                <input type="checkbox" name="export_import_settings_use_filter_id" value="1" /> <?php echo $entry_settings_use_filter_id; ?>
                                                <?php } ?>
                                            </label>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                    <tr>
                                        <td>
                                            <label>
                                                <?php if ($settings_use_export_cache) { ?>
                                                <input type="checkbox" name="export_import_settings_use_export_cache" value="1" checked="checked" /> <?php echo $entry_settings_use_export_cache; ?>
                                                <?php } else { ?>
                                                <input type="checkbox" name="export_import_settings_use_export_cache" value="1" /> <?php echo $entry_settings_use_export_cache; ?>
                                                <?php } ?>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>
                                                <?php if ($settings_use_import_cache) { ?>
                                                <input type="checkbox" name="export_import_settings_use_import_cache" value="1" checked="checked" /> <?php echo $entry_settings_use_import_cache; ?>
                                                <?php } else { ?>
                                                <input type="checkbox" name="export_import_settings_use_import_cache" value="1" /> <?php echo $entry_settings_use_import_cache; ?>
                                                <?php } ?>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="buttons"><a onclick="updateSettings();" class="btn btn-primary"><span><?php echo $button_settings; ?></span></a></td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript"><!--
    function check_range_type(export_type) {
        if ((export_type=='p') || (export_type=='c')) {
            $('#range_type').show();
            $('#range_type_id').prop('checked',true);
            $('#range_type_page').prop('checked',false);
            $('.id').show();
            $('.page').hide();
        } else {
            $('#range_type').hide();
        }
    }

    $(document).ready(function() {

        check_range_type($('input[name=export_type]:checked').val());

        $("#range_type_id").click(function() {
            $(".page").hide();
            $(".id").show();
        });

        $("#range_type_page").click(function() {
            $(".id").hide();
            $(".page").show();
        });

        $('input[name=export_type]').click(function() {
            check_range_type($(this).val());
        });

        $('span.close').click(function() {
            $(this).parent().remove();
        });

        $('a[data-toggle="tab"]').click(function() {
            $('#export_import_notification').remove();
        });
    });

    function checkFileSize(id) {
        // See also http://stackoverflow.com/questions/3717793/javascript-file-upload-size-validation for details
        var input, file, file_size;

        if (!window.FileReader) {
            // The file API isn't yet supported on user's browser
            return true;
        }

        input = document.getElementById(id);
        if (!input) {
            // couldn't find the file input element
            return true;
        }
        else if (!input.files) {
            // browser doesn't seem to support the `files` property of file inputs
            return true;
        }
        else if (!input.files[0]) {
            // no file has been selected for the upload
            alert( "<?php echo $error_select_file; ?>" );
            return false;
        }
        else {
            file = input.files[0];
            file_size = file.size;
            <?php if (!empty($post_max_size)) { ?>
            // check against PHP's post_max_size
            post_max_size = <?php echo $post_max_size; ?>;
            if (file_size > post_max_size) {
                alert( "<?php echo $error_post_max_size; ?>" );
                return false;
            }
            <?php } ?>
            <?php if (!empty($upload_max_filesize)) { ?>
            // check against PHP's upload_max_filesize
            upload_max_filesize = <?php echo $upload_max_filesize; ?>;
            if (file_size > upload_max_filesize) {
                alert( "<?php echo $error_upload_max_filesize; ?>" );
                return false;
            }
            <?php } ?>
            return true;
        }
    }

    function uploadData() {
        if (checkFileSize('upload')) {
            $('#import').submit();
        }
    }

    function isNumber(txt) {
        var regExp=/^[\d]{1,}$/;
        return regExp.test(txt);
    }

    function validateExportForm(id) {
        var val = $("input[name=range_type]:checked").val();
        var min = $("input[name=min]").val();
        var max = $("input[name=max]").val();

        if ((min=='') && (max=='')) {
            return true;
        }

        if (!isNumber(min) || !isNumber(max)) {
            alert("<?php echo $error_param_not_number; ?>");
            return false;
        }

        var export_type = $('input[name=export_type]:checked').val();
        var count_item = (export_type=='p') ? <?php echo $count_product-1; ?> : <?php echo $count_category-1; ?>;
        var batchNo = parseInt(count_item/parseInt(min))+1; // Maximum number of item-batches, namely, item number/min, and then rounded up (that is, integer plus 1)
        var minItemId = parseInt((export_type=='c') ? <?php echo $min_category_id; ?> : <?php echo $min_product_id; ?>);
        var maxItemId = parseInt((export_type=='c') ? <?php echo $max_category_id; ?> : <?php echo $max_product_id; ?>);

        if (val=="page") {  // Min for the batch size, Max for the batch number
            if (parseInt(max) <= 0) {
                alert("<?php echo $error_batch_number; ?>");
                return false;
            }
            if (parseInt(max) > batchNo) {
                alert("<?php echo $error_page_no_data; ?>");
                return false;
            } else {
                $("input[name=max]").val(parseInt(max)+1);
            }
        } else {
            if (minItemId <= 0) {
                alert("<?php echo $error_min_item_id; ?>");
                return false;
            }
            if (parseInt(min) > maxItemId || parseInt(max) < minItemId || parseInt(min) > parseInt(max)) {
                alert("<?php echo $error_id_no_data; ?>");
                return false;
            }
        }
        return true;
    }

    function downloadData() {
        if (validateExportForm('export')) {
            $('#export').submit();
        }
    }

    function updateSettings() {
        $('#settings').submit();
    }
    //--></script>
</div>
<?php echo $footer; ?>
