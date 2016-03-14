<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-success"><i class="fa fa-plus"></i></a>
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
                        <div class="col-lg-12">
                            <div class="input-group">
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="caret"></span>
                                    </button>
                                    <button type="button" onclick="filter();" class="btn btn-default"><div class="filter-type"><?php echo $column_country; ?></div></button>
                                    <ul class="dropdown-menu">
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $column_country; ?>', 'filter_country');"><?php echo $column_country; ?></a></li>
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $column_name; ?>', 'filter_zone_name');"><?php echo $column_name; ?></a></li>
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $column_code; ?>', 'filter_zone_code');"><?php echo $column_code; ?></a></li>
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_status; ?>', 'filter_status');"><?php echo $entry_status; ?></a></li>
                                    </ul>
                                </div>
                                <input type="text" name="filter_country"  value="<?php echo $filter_country; ?>" placeholder="<?php echo $column_country; ?>" id="input-country" class="form-control filter">
                                <input type="text" name="filter_zone_name"  value="<?php echo $filter_zone_name; ?>" placeholder="<?php echo $column_name; ?>" id="input-name" class="form-control filter hidden">
                                <input type="text" name="filter_zone_code"  value="<?php echo $filter_zone_code; ?>" placeholder="<?php echo $column_code; ?>" id="input-zone-code" class="form-control filter hidden">
                                <select name="filter_status" id="input-status" class="form-control filter hidden">
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
                    </div>
                    <?php if (!empty($filter_country) || !empty($filter_zone_name) || !empty($filter_zone_code) || !empty($filter_status)) { ?>
                    <div class="row">
                        <div class="col-lg-12 filter-tag">
                            <?php if ($filter_country) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $column_country; ?>:</label> <label class="filter-label"> <?php echo $filter_country; ?></label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_country');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                            <?php if ($filter_zone_name) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $column_name; ?>:</label> <label class="filter-label"> <?php echo $filter_zone_name; ?></label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_zone_name');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                            <?php if (isset($filter_zone_code)) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $column_code; ?>:</label> <label class="filter-label"> <?php echo ($filter_zone_code) ? $text_enabled : $text_disabled; ?></label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_zone_code');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                            <?php if ($filter_status) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $entry_status; ?>:</label> <label class="filter-label"> <?php echo $filter_status; ?></label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_status');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-zone">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <td style="width: 70px;" class="text-center">
                                    <div class="bulk-action">
                                        <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" />
                                        <span class="bulk-caret"><i class="fa fa-caret-down"></i></span>
                                        <span class="item-selected"></span>
                                        <span class="bulk-action-button">
                                          <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                              <b><?php echo $text_bulk_action; ?></b>
                                              <span class="caret"></span>
                                          </a>
                                          <ul class="dropdown-menu dropdown-menu-left alerts-dropdown">
                                              <li class="dropdown-header"><?php echo $text_bulk_action; ?></li>
                                              <li><a onclick="changeStatus(1)"><i class="fa fa-check-circle text-success"></i> <?php echo $button_enable; ?></a></li>
                                              <li><a onclick="changeStatus(0)"><i class="fa fa-times-circle text-danger"></i> <?php echo $button_disable; ?></a></li>
                                              <li><a onclick="confirmItem('<?php echo $text_confirm_title; ?>', '<?php echo $text_confirm; ?>');"><i class="fa fa-trash-o"></i> <?php echo $button_delete; ?></a></li>
                                          </ul>
                                        </span>
                                    </div></td>
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
                                <td class="text-left"><?php if ($sort == 'z.status') { ?>
                                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                                    <?php } ?></td>
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
                                <td class="text-left">
                                    <a href="<?php echo $zone['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary btn-sm btn-basic-list"><i class="fa fa-pencil"></i></a>
                                    <?php echo $zone['country']; ?></td>
                                <td class="text-left"><?php echo $zone['name']; ?></td>
                                <td class="text-left"><?php echo $zone['code']; ?></td>
                                <td class="text-left"><?php echo $zone['status']; ?></td>
                            </tr>
                            <?php } ?>
                            <?php } else { ?>
                            <tr>
                                <td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
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
        filter();
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
        filter();
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
        filter();
    }
});
//--></script>
<script type="text/javascript"><!--
function filter() {
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
}
//--></script>
<?php echo $footer; ?>
