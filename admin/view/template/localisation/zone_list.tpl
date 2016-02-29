<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-success"><i class="fa fa-plus"></i></a>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_enable; ?>" class="btn btn-default" onclick="changeStatus(1)"><i class="fa fa-check-circle text-success"></i></button>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_disable; ?>" class="btn btn-default" onclick="changeStatus(0)"><i class="fa fa-times-circle text-danger"></i></button>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-zone').submit() : false;"><i class="fa fa-trash-o"></i></button>
            </div>
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
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label" for="input-filter-country"><?php echo $column_country; ?></label>
                                <input type="text" name="filter_country" value="<?php echo $filter_country; ?>" placeholder="<?php echo $column_country; ?>" id="input-filter-country" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="input-filter-zone-name"><?php echo $column_name; ?></label>
                                <input type="text" name="filter_zone_name" value="<?php echo $filter_zone_name; ?>" placeholder="<?php echo $column_name; ?>" id="input-filter-zone-name" class="form-control" />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label" for="input-filter-zone-code"><?php echo $column_code; ?></label>
                                <input type="text" name="filter_zone_code" value="<?php echo $filter_zone_code; ?>" placeholder="<?php echo $column_code; ?>" id="input-filter-zone-code" class="form-control" />
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
                            <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
                        </div>
                    </div>
                </div>
                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-zone">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                                <td class="text-left"><?php if ($sort == 'c.name') { ?>
                                    <a href="<?php echo $sort_country; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_country; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_country; ?>"><?php echo $column_country; ?></a>
                                    <?php } ?></td>
                                <td class="text-left"><?php if ($sort == 'z.name') { ?>
                                    <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                                    <?php } ?></td>
                                <td class="text-left"><?php if ($sort == 'z.code') { ?>
                                    <a href="<?php echo $sort_code; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_code; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_code; ?>"><?php echo $column_code; ?></a>
                                    <?php } ?></td>
                                <td class="text-left"><?php if ($sort == 'status') { ?>
                                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                                    <?php } ?></td> 
                                <td class="text-right"><?php echo $column_action; ?></td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ($zones) { ?>
                            <?php foreach ($zones as $zone) { ?>
                            <tr>
                                <td class="text-center"><?php if (in_array($zone['zone_id'], $selected)) { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $zone['zone_id']; ?>" checked="checked" />
                                    <?php } else { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $zone['zone_id']; ?>" />
                                    <?php } ?></td>
                                <td class="text-left"><?php echo $zone['country']; ?></td>
                                <td class="text-left"><?php echo $zone['name']; ?></td>
                                <td class="text-left"><?php echo $zone['code']; ?></td>
                                <td class="text-left"><?php echo $zone['status']; ?></td>
                                <td class="text-right"><a href="<?php echo $zone['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
                            </tr>
                            <?php } ?>
                            <?php } else { ?>
                            <tr>
                                <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
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
    var url = 'index.php?route=localisation/zone&token=<?php echo $token; ?>';

    var filter_country = $('input[name=\'filter_country\']').val();

    if (filter_country) {
        url += '&filter_country=' + encodeURIComponent(filter_country);
    }

    var filter_zone_name = $('input[name=\'filter_zone_name\']').val();

    if (filter_zone_name) {
        url += '&filter_zone_name=' + encodeURIComponent(filter_zone_name);
    }

    var filter_zone_code = $('input[name=\'filter_zone_code\']').val();

    if (filter_zone_code) {
        url += '&filter_zone_code=' + encodeURIComponent(filter_zone_code);
    }

    var filter_status = $('select[name=\'filter_status\']').val();

    if (filter_status != '*') {
        url += '&filter_status=' + encodeURIComponent(filter_status);
    }

    location = url;
});
//--></script>
<script type="text/javascript"><!--
$('input[name=\'filter_country\']').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=localisation/zone/autocomplete&token=<?php echo $token; ?>&filter_country=' +  encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['country'],
                        value: item['country_id']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('input[name=\'filter_country\']').val(item['label']);
    }
});

$('input[name=\'filter_zone_name\']').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=localisation/zone/autocomplete&token=<?php echo $token; ?>&filter_zone_name=' +  encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['name'],
                        value: item['zone_id']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('input[name=\'filter_zone_name\']').val(item['label']);
    }
});

$('input[name=\'filter_zone_code\']').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=localisation/zone/autocomplete&token=<?php echo $token; ?>&filter_zone_code=' +  encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['code'],
                        value: item['zone_id']
                    }
                }));

            }
        });
    },
    'select': function(item) {
        $('input[name=\'filter_zone_code\']').val(item['label']);
    }
});
//--></script>
<?php echo $footer; ?>
