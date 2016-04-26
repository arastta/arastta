<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-success"><i class="fa fa-plus"></i></a>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirmItem('<?php echo $text_confirm_title; ?>', '<?php echo $text_confirm; ?>');"><i class="fa fa-trash-o"></i></button>
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
                                    <button type="button" onclick="filter();" class="btn btn-default"><div class="filter-type"><?php echo $column_name; ?></div></button>
                                    <ul class="dropdown-menu">
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $column_name; ?>', 'filter_country');"><?php echo $column_name; ?></a></li>
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $column_iso_code_2; ?>', 'filter_iso_code_2');"><?php echo $column_iso_code_2; ?></a></li>
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $column_iso_code_3; ?>', 'filter_iso_code_3');"><?php echo $column_iso_code_3; ?></a></li>
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_status; ?>', 'filter_status');"><?php echo $entry_status; ?></a></li>
                                    </ul>
                                </div>
                                <input type="text" name="filter_country"  value="<?php echo $filter_country; ?>" id="input-country" class="form-control filter">
                                <input type="text" name="filter_iso_code_2"  value="<?php echo $filter_iso_code_2; ?>" id="input-iso-code-2" class="form-control filter hidden">
                                <input type="text" name="filter_iso_code_3"  value="<?php echo $filter_iso_code_3; ?>" id="input-iso-code-3" class="form-control filter hidden">
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
                    <?php if (!empty($filter_country) || !empty($filter_iso_code_2) || !empty($filter_iso_code_3) || !empty($filter_status)) { ?>
                    <div class="row">
                        <div class="col-lg-12 filter-tag">
                            <?php if ($filter_country) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $column_name; ?>:</label> <label class="filter-label"> <?php echo $filter_country; ?></label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_country');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                            <?php if ($filter_iso_code_2) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $column_iso_code_2; ?>:</label> <label class="filter-label"> <?php echo $filter_iso_code_2; ?></label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_iso_code_2');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                            <?php if (isset($filter_iso_code_3)) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $column_iso_code_3; ?>:</label> <label class="filter-label"> <?php echo $filter_iso_code_3; ?></label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_iso_code_3');"><i class="fa fa-times"></i></a>
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
                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-country">
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
                                <td class="text-left"><?php if ($sort == 'name') { ?>
                                    <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                                    <?php } ?></td>
                                <td class="text-left"><?php if ($sort == 'iso_code_2') { ?>
                                    <a href="<?php echo $sort_iso_code_2; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_iso_code_2; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_iso_code_2; ?>"><?php echo $column_iso_code_2; ?></a>
                                    <?php } ?></td>
                                <td class="text-left"><?php if ($sort == 'iso_code_3') { ?>
                                    <a href="<?php echo $sort_iso_code_3; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_iso_code_3; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_iso_code_3; ?>"><?php echo $column_iso_code_3; ?></a>
                                    <?php } ?></td>
                                <td class="text-left"><?php if ($sort == 'status') { ?>
                                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                                    <?php } ?></td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ($countries) { ?>
                            <?php foreach ($countries as $country) { ?>
                            <tr>
                                <td class="text-center"><?php if (in_array($country['country_id'], $selected)) { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $country['country_id']; ?>" checked="checked" />
                                    <?php } else { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $country['country_id']; ?>" />
                                    <?php } ?></td>
                                <td class="text-left">
                                    <a href="<?php echo $country['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary btn-sm btn-basic-list"><i class="fa fa-pencil"></i></a>
                                    <?php echo $country['name']; ?></td>
                                <td class="text-left"><?php echo $country['iso_code_2']; ?></td>
                                <td class="text-left"><?php echo $country['iso_code_3']; ?></td>
                                <td class="text-left"><?php echo $country['status']; ?></td>
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
            url: 'index.php?route=localisation/country/autocomplete&token=<?php echo $token; ?>&filter_country=' +  encodeURIComponent(request),
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

$('input[name=\'filter_iso_code_2\']').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=localisation/country/autocomplete&token=<?php echo $token; ?>&filter_iso_code_2=' +  encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['iso_code_2'],
                        value: item['country_id']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('input[name=\'filter_iso_code_2\']').val(item['label']);
        filter();
    }
});

$('input[name=\'filter_iso_code_3\']').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=localisation/country/autocomplete&token=<?php echo $token; ?>&filter_iso_code_3=' +  encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['iso_code_3'],
                        value: item['country_id']
                    }
                }));

            }
        });
    },
    'select': function(item) {
        $('input[name=\'filter_iso_code_3\']').val(item['label']);
        filter();
    }
});
//--></script>
<script type="text/javascript"><!--
function filter() {
    var url = 'index.php?route=localisation/country&token=<?php echo $token; ?>';

    var filter_country = $('input[name=\'filter_country\']').val();

    if (filter_country) {
        url += '&filter_country=' + encodeURIComponent(filter_country);
    }

    var filter_iso_code_2 = $('input[name=\'filter_iso_code_2\']').val();

    if (filter_iso_code_2) {
        url += '&filter_iso_code_2=' + encodeURIComponent(filter_iso_code_2);
    }

    var filter_iso_code_3 = $('input[name=\'filter_iso_code_3\']').val();

    if (filter_iso_code_3) {
        url += '&filter_iso_code_3=' + encodeURIComponent(filter_iso_code_3);
    }

    var filter_status = $('select[name=\'filter_status\']').val();

    if (filter_status != '*') {
        url += '&filter_status=' + encodeURIComponent(filter_status);
    }

    location = url;
}
//--></script>
<?php echo $footer; ?>
