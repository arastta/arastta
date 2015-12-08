<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
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
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="input-text"><?php echo $entry_text; ?></label>
                                <input type="text" name="filter_text" value="<?php echo $filter_text; ?>" placeholder="<?php echo $entry_text; ?>" id="input-text" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="input-context"><?php echo $entry_context; ?></label>
                                <input type="text" name="filter_context" value="<?php echo $filter_context; ?>" placeholder="<?php echo $entry_context; ?>" id="input-context" class="form-control" />
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
                                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="input-type"><?php echo $entry_type; ?></label>
                                <select name="filter_type" id="input-type" class="form-control">
                                    <option value=""></option>
                                    <?php foreach ($types as $type) { ?>
                                    <?php if ($filter_type == $type['id']) { ?>
                                    <option value="<?php echo $type['id']; ?>" selected="selected"><?php echo $type['value']; ?></option>
                                    <?php } else { ?>
                                    <option value="<?php echo $type['id']; ?>"><?php echo $type['value']; ?></option>
                                    <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
                                <select name="filter_status" id="input-status" class="form-control">
                                    <option value="*"></option>
                                    <?php if ($filter_status) { ?>
                                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                    <?php } else { ?>
                                    <option value="1"><?php echo $text_enabled; ?></option>
                                    <?php } ?>
                                    <?php if (!$filter_status && !is_null($filter_status)) { ?>
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
                <form action="<?php //echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-email-template">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                                <td class="text-left"><?php if ($sort == 'text') { ?>
                                    <a href="<?php echo $sort_text; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_text; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_text; ?>"><?php echo $column_text; ?></a>
                                    <?php } ?></td>
                                <td class="text-left"><?php if ($sort == 'type') { ?>
                                    <a href="<?php echo $sort_type; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_type; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_type; ?>"><?php echo $column_type; ?></a>
                                    <?php } ?></td>
                                <td class="text-left"><?php if ($sort == 'context') { ?>
                                    <a href="<?php echo $sort_context; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_context; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_context; ?>"><?php echo $column_context; ?></a>
                                    <?php } ?></td>
                                <td class="text-left"><?php echo $column_status; ?></td>
                                <td class="text-right"><?php echo $column_action; ?></td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ($emailTemplates) { ?>
                            <?php foreach ($emailTemplates as $email_template) { ?>
                            <tr>
                                <td class="text-center"><?php if (in_array($email_template['id'], $selected)) { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $email_template['id']; ?>" checked="checked" />
                                    <?php } else { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $email_template['id']; ?>" />
                                    <?php } ?></td>
                                <td class="text-left"><?php echo $email_template['text']; ?></td>
                                <td class="text-left"><?php echo ucwords($email_template['type']); ?></td>
                                <td class="text-left"><?php echo $email_template['context']; ?></td>
                                <td class="text-left"><?php echo empty($email_template['status']) ? $text_disabled : $text_enabled; ?></td>
                                <td class="text-right"><a href="<?php echo $email_template['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
                            </tr>
                            <?php } ?>
                            <?php } else { ?>
                            <tr>
                                <td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </form>
                <div class="row">
                    <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
                    <div class="col-sm-6 text-right"><?php echo $results; ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript"><!--
$('#button-filter').on('click', function() {
    var url = 'index.php?route=system/email_template&token=<?php echo $token; ?>';

    var filter_text = $('input[name=\'filter_text\']').val();

    if (filter_text) {
        url += '&filter_text=' + encodeURIComponent(filter_text);
    }

    var filter_context = $('input[name=\'filter_context\']').val();

    if (filter_context) {
        url += '&filter_context=' + encodeURIComponent(filter_context);
    }

    var filter_name = $('input[name=\'filter_name\']').val();

    if (filter_name) {
        url += '&filter_name=' + encodeURIComponent(filter_name);
    }

    var filter_type = $('select[name=\'filter_type\']').val();

    if (filter_type != '') {
        url += '&filter_type=' + encodeURIComponent(filter_type);
    }

    var filter_status = $('select[name=\'filter_status\']').val();

    if (filter_status != '*') {
        url += '&filter_status=' + encodeURIComponent(filter_status);
    }

    location = url;
});
//--></script>
<script type="text/javascript"><!--
$('input[name=\'filter_name\']').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=system/email_template/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
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
    },
    'select': function(item) {
        $('input[name=\'filter_name\']').val(item['label']);
    }
});
//--></script>

<?php echo $footer; ?>
