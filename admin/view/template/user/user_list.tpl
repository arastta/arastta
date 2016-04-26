<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-success"><i class="fa fa-plus"></i></a>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_enable; ?>" class="btn btn-default" onclick="changeStatus(1)"><i class="fa fa-check-circle text-success"></i></button>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_disable; ?>" class="btn btn-default" onclick="changeStatus(0)"><i class="fa fa-times-circle text-danger"></i></button>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-user').submit() : false;"><i class="fa fa-trash-o"></i></button>
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
                                <div class="form-group">
                                    <label class="control-label" for="input-firstname"><?php echo $entry_firstname; ?></label>
                                    <input type="text" name="filter_firstname" value="<?php echo $filter_firstname; ?>" placeholder="<?php echo $entry_firstname; ?>" id="input-firstname" class="form-control" />
                                </div>
                                <label class="control-label" for="input-user-group"><?php echo $entry_user_group; ?></label>
                                <input type="text" name="filter_user_group" value="<?php echo $filter_user_group; ?>" placeholder="<?php echo $entry_user_group; ?>" id="input-user-group" class="form-control" />
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="input-lastname"><?php echo $entry_lastname; ?></label>
                                <input type="text" name="filter_lastname" value="<?php echo $filter_lastname; ?>" placeholder="<?php echo $entry_lastname; ?>" id="input-lastname" class="form-control" />
                            </div>
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
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="input-email"><?php echo $entry_email; ?></label>
                                <input type="text" name="filter_email" value="<?php echo $filter_email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
                            </div>
                            <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
                        </div>
                    </div>
                </div>
                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-user">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                                <td class="text-left"><?php if ($sort == 'firstname') { ?>
                                    <a href="<?php echo $sort_firstname; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_firstname; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_firstname; ?>"><?php echo $column_firstname; ?></a>
                                    <?php } ?></td>
                                <td class="text-left"><?php if ($sort == 'lastname') { ?>
                                    <a href="<?php echo $sort_lastname; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_lastname; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_lastname; ?>"><?php echo $column_lastname; ?></a>
                                    <?php } ?></td>
                                <td class="text-left"><?php if ($sort == 'email') { ?>
                                    <a href="<?php echo $sort_email; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_email; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_email; ?>"><?php echo $column_email; ?></a>
                                    <?php } ?></td>
                                <td class="text-left"><?php echo $column_user_group; ?></td>
                                <td class="text-left"><?php if ($sort == 'status') { ?>
                                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                                    <?php } ?></td>
                                <td class="text-left"><?php if ($sort == 'date_added') { ?>
                                    <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                                    <?php } ?></td>
                                <td class="text-right"><?php echo $column_action; ?></td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ($users) { ?>
                            <?php foreach ($users as $user) { ?>
                            <tr>
                                <td class="text-center"><?php if (in_array($user['user_id'], $selected)) { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $user['user_id']; ?>" checked="checked" />
                                    <?php } else { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $user['user_id']; ?>" />
                                    <?php } ?></td>
                                <td class="text-left"><?php echo $user['firstname']; ?></td>
                                <td class="text-left"><?php echo $user['lastname']; ?></td>
                                <td class="text-left"><?php echo $user['email']; ?></td>
                                <td class="text-left"><?php echo $user['user_group']; ?></td>
                                <td class="text-left"><?php echo $user['status']; ?></td>
                                <td class="text-left"><?php echo $user['date_added']; ?></td>
                                <td class="text-right"><a href="<?php echo $user['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
                            </tr>
                            <?php } ?>
                            <?php } else { ?>
                            <tr>
                                <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
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
    var url = 'index.php?route=user/user&token=<?php echo $token; ?>';

    var filter_user_group = $('input[name=\'filter_user_group\']').val();

    if (filter_user_group) {
        url += '&filter_user_group=' + encodeURIComponent(filter_user_group);
    }

    var filter_firstname = $('input[name=\'filter_firstname\']').val();

    if (filter_firstname) {
        url += '&filter_firstname=' + encodeURIComponent(filter_firstname);
    }

    var filter_lastname = $('input[name=\'filter_lastname\']').val();

    if (filter_lastname) {
        url += '&filter_lastname=' + encodeURIComponent(filter_lastname);
    }

    var filter_email = $('input[name=\'filter_email\']').val();

    if (filter_email) {
        url += '&filter_email=' + encodeURIComponent(filter_email);
    }

    var filter_status = $('select[name=\'filter_status\']').val();

    if (filter_status != '*') {
        url += '&filter_status=' + encodeURIComponent(filter_status);
    }

    location = url;
});
//--></script>
<script type="text/javascript"><!--
$('input[name=\'filter_user_group\']').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=user/user/autocomplete&token=<?php echo $token; ?>&filter_user_group=' +  encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['user_group'],
                        value: item['user_group_id']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('input[name=\'filter_user_group\']').val(item['label']);
    }
});

$('input[name=\'filter_firstname\']').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=user/user/autocomplete&token=<?php echo $token; ?>&filter_firstname=' +  encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['firstname'],
                        value: item['user_id']
                    }
                }));

            }
        });
    },
    'select': function(item) {
        $('input[name=\'filter_firstname\']').val(item['label']);
    }
});

$('input[name=\'filter_lastname\']').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=user/user/autocomplete&token=<?php echo $token; ?>&filter_lastname=' +  encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['lastname'],
                        value: item['user_id']
                    }
                }));

            }
        });
    },
    'select': function(item) {
        $('input[name=\'filter_lastname\']').val(item['label']);
    }
});

$('input[name=\'filter_email\']').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=user/user/autocomplete&token=<?php echo $token; ?>&filter_email=' +  encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['email'],
                        value: item['user_id']
                    }
                }));

            }
        });
    },
    'select': function(item) {
        $('input[name=\'filter_email\']').val(item['label']);
    }
});
//--></script>
<?php echo $footer; ?> 
