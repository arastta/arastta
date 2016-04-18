<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <h1><?php echo $heading_title; ?></h1>
            <div class="pull-right">
                <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-success" data-original-title="<?php echo $button_add; ?>"><i class="fa fa-plus"></i></a>
                <a href="<?php echo $upload; ?>" data-toggle="tooltip" title="<?php echo $button_upload; ?>" class="btn btn-default" data-original-title="<?php echo $button_upload; ?>"><i class="fa fa-upload"></i></a>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_enable; ?>" class="btn btn-default" onclick="changeStatus(1)" <?php echo ($filter_type == 'module') ? 'disabled' : ''; ?>><i class="fa fa-check-circle text-success"></i></button>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_disable; ?>" class="btn btn-default" onclick="changeStatus(0)" <?php echo ($filter_type == 'module') ? 'disabled' : ''; ?>><i class="fa fa-times-circle text-danger"></i></button>
            </div>
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
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
                <div class="pull-right">
                    <button type="button" data-toggle="tooltip" title="<?php echo $button_show_filter; ?>" class="btn btn-primary btn-sm" id="showFilter"><i class="fa fa-eye"></i></button>
                    <button type="button" data-toggle="tooltip" title="<?php echo $button_hide_filter; ?>" class="btn btn-primary btn-sm" id="hideFilter"><i class="fa fa-eye-slash"></i></button>
                </div>
            </div>
            <div class="panel-body">
                <div class="well" style="display:none;">
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="control-label" for="input-name"><?php echo $column_name; ?></label>
                                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $column_name; ?>" id="input-name" class="form-control" autocomplete="off" />
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="input-author"><?php echo $column_author; ?></label>
                                <input type="text" name="filter_author" value="<?php echo $filter_author; ?>" placeholder="<?php echo $column_author; ?>" id="input-author" class="form-control" autocomplete="off" />
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label"><?php echo $column_type; ?></label>
                                <select name="filter_type" class="form-control">
                                    <option value="*"></option>
                                    <option value="payment" <?php echo ($filter_type == 'payment') ? 'selected="selected"' : ''; ?> ><?php echo $text_payment; ?></option>
                                    <option value="shipping" <?php echo ($filter_type == 'shipping') ? 'selected="selected"' : ''; ?> ><?php echo $text_shipping; ?></option>
                                    <option value="module" <?php echo ($filter_type == 'module') ? 'selected="selected"' : ''; ?> ><?php echo $text_module; ?></option>
                                    <option value="total" <?php echo ($filter_type == 'total') ? 'selected="selected"' : ''; ?> ><?php echo $text_total; ?></option>
                                    <option value="feed" <?php echo ($filter_type == 'feed') ? 'selected="selected"' : ''; ?> ><?php echo $text_feed; ?></option>
                                    <option value="editor" <?php echo ($filter_type == 'editor') ? 'selected="selected"' : ''; ?> ><?php echo $text_editor; ?></option>
                                    <option value="captcha" <?php echo ($filter_type == 'captcha') ? 'selected="selected"' : ''; ?> ><?php echo $text_captcha; ?></option>
                                    <option value="twofactorauth" <?php echo ($filter_type == 'twofactorauth') ? 'selected="selected"' : ''; ?> ><?php echo $text_twofactorauth; ?></option>
                                    <option value="other" <?php echo ($filter_type == 'other') ? 'selected="selected"' : ''; ?> ><?php echo $text_other; ?></option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label"><?php echo $column_status; ?></label>
                                <select name="filter_status" class="form-control">
                                    <option value="*"></option>
                                    <?php if ($filter_status) { ?>
                                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                    <?php } else { ?>
                                    <option value="1"><?php echo $text_enabled; ?></option>
                                    <?php } ?>
                                    <?php if (!is_null($filter_status) && !$filter_status) { ?>
                                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                    <?php } else { ?>
                                    <option value="0"><?php echo $text_disabled; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <form id="form-extension" method="post">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <td width="1" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
                                <td class="text-left"><?php if ($sort == 'name') { ?>
                                    <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                                    <?php } ?>
                                </td>
                                <td class="text-left"><?php if ($sort == 'author') { ?>
                                    <a href="<?php echo $sort_author; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_author; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_author; ?>"><?php echo $column_author; ?></a>
                                    <?php } ?>
                                </td>
                                <td class="text-left column-120"><?php echo $column_version; ?></td>
                                <?php if (!$filter_type) { ?>
                                <td class="text-left"><?php if ($sort == 'type') { ?>
                                    <a href="<?php echo $sort_type; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_type; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_type; ?>"><?php echo $column_type; ?></a>
                                    <?php } ?>
                                </td>
                                <?php } ?>
                                <td class="text-left column-120"><?php echo $column_status; ?></td>
                                <td class="text-right column-120"><?php echo $column_sort_order; ?></td>
                                <td class="text-right column-120"><?php echo $column_action; ?></td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ($extensions) { ?>
                            <?php foreach ($extensions as $extension) { ?>
                            <tr>
                                <td class="text-center"><input type="checkbox" name="selected[]" value="<?php echo $extension['type'] . '/' . $extension['code']; ?>" /></td>
                                <td class="text-left">
                                    <?php echo $extension['name']; ?>
                                    <?php if ($extension['instances']) { ?>
                                    <?php foreach ($extension['instances'] as $instance) { ?>
                                    <table>
                                        <tr>
                                            <td class="text-left">&nbsp;</td>
                                            <td class="text-left" colspan="6">
                                                &nbsp;&nbsp;&nbsp;&#8618;&nbsp;&nbsp;<?php echo $instance['name']; ?>&nbsp;&nbsp;
                                                <a href="<?php echo $instance['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>"><i class="fa fa-pencil"></i></a>
                                                <a onclick="confirm('<?php echo $text_confirm; ?>') ? location.href='<?php echo $instance['delete']; ?>' : false;" data-toggle="tooltip" title="<?php echo $button_delete; ?>"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    </table>
                                    <?php } ?>
                                    <?php } ?>
                                </td>
                                <td class="text-left"><?php echo $extension['author'] ?></td>
                                <td class="text-left"><?php echo $extension['version'] ?></td>
                                <?php if (!$filter_type) { ?>
                                <td class="text-left"><?php echo $extension['type_name'] ?></td>
                                <?php } ?>
                                <td class="text-left"><?php echo $extension['status'] ?></td>
                                <td class="text-right"><?php echo $extension['sort_order']; ?></td>
                                <td class="text-right">
                                    <a href="<?php echo $extension['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                                    <a onclick="confirm('<?php echo $text_confirm; ?>') ? location.href='<?php echo $extension['uninstall']; ?>' : false;" data-toggle="tooltip" title="<?php echo $button_uninstall; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a>
                                </td>
                            </tr>
                            <?php } ?>
                            <?php } else { ?>
                            <tr>
                                <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </form>
                    <div class="row">
                        <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
                        <div class="col-sm-6 text-right"><?php echo $results; ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript"><!--
$('#button-filter').on('click', function() {
    url = 'index.php?route=extension/extension&token=<?php echo $token; ?>';

    var filter_name = $('input[name=\'filter_name\']').val();

    if (filter_name) {
        url += '&filter_name=' + encodeURIComponent(filter_name);
    }

    var filter_author = $('input[name=\'filter_author\']').val();

    if (filter_author) {
        url += '&filter_author=' + encodeURIComponent(filter_author);
    }

    var filter_type = $('select[name=\'filter_type\']').val();

    if (filter_type != '*') {
        url += '&filter_type=' + encodeURIComponent(filter_type);
    }

    var filter_status = $('select[name=\'filter_status\']').val();

    if (filter_status != '*') {
        url += '&filter_status=' + encodeURIComponent(filter_status);
    }

    location = url;
});
//--></script>
<?php echo $footer; ?>
