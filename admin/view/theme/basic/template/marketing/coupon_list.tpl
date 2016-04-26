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
                                    <button type="button" onclick="filter();" class="btn btn-default"><div class="filter-type"><?php echo $column_name; ?></div></button>
                                    <ul class="dropdown-menu">
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $column_name; ?>', 'filter_name');"><?php echo $column_name; ?></a></li>
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $column_code; ?>', 'filter_code');"><?php echo $column_code; ?></a></li>
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $column_date_start; ?>', 'filter_date_start');"><?php echo $column_date_start; ?></a></li>
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $column_date_end; ?>', 'filter_date_end');"><?php echo $column_date_end; ?></a></li>
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $column_discount; ?>', 'filter_discount');"><?php echo $column_discount; ?></a></li>
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $column_status; ?>', 'filter_status');"><?php echo $column_status; ?></a></li>
                                    </ul>
                                </div>
                                <input type="text" name="filter_name"  value="<?php echo $filter_name; ?>" placeholder="<?php echo $column_name; ?>" id="input-name" class="form-control filter">
                                <input type="text" name="filter_code"  value="<?php echo $filter_code; ?>" placeholder="<?php echo $column_code; ?>" id="input-code" class="form-control filter hidden">
                                <div class="input-group date filter hidden filter_date_start">
                                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="<?php echo $column_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control filter" />
                                  <span class="input-group-btn">
                                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                  </span></div>
                                <div class="input-group date filter hidden filter_date_end">
                                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $column_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control filter hidden" />
                                  <span class="input-group-btn">
                                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                  </span></div>
                                <input type="text" name="filter_discount" value="<?php echo $filter_discount; ?>" placeholder="<?php echo $column_discount; ?>" id="input-discount" class="form-control filter hidden" />
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
                    <?php if (!empty($filter_name) || !empty($filter_code) || !empty($filter_date_start) || !empty($filter_date_end) || !empty($filter_discount) || isset($filter_status)) { ?>
                    <div class="row">
                        <div class="col-lg-12 filter-tag">
                            <?php if ($filter_name) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $column_name; ?>:</label> <label class="filter-label"> <?php echo $filter_name; ?></label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_name');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                            <?php if ($filter_code) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $column_code; ?>:</label> <label class="filter-label"> <?php echo $filter_code; ?></label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_code');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                            <?php if ($filter_date_start) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $column_date_start; ?>:</label> <label class="filter-label"> <?php echo $filter_date_start; ?></label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_date_start');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                            <?php if ($filter_date_end) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $column_date_end; ?>:</label> <label class="filter-label"> <?php echo $filter_date_end; ?></label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_date_end');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                            <?php if ($filter_discount) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $column_discount; ?>:</label> <label class="filter-label"> <?php echo $filter_discount; ?></label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_discount');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                            <?php if (isset($filter_status)) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $column_status; ?>:</label> <label class="filter-label"> <?php echo ($filter_status) ? $text_enabled : $text_disabled; ?></label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_status');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-coupon">
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
                                <td class="text-left"><?php if ($sort == 'cd.name') { ?>
                                    <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                                    <?php } ?></td>
                                <td class="text-left"><?php if ($sort == 'c.code') { ?>
                                    <a href="<?php echo $sort_code; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_code; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_code; ?>"><?php echo $column_code; ?></a>
                                    <?php } ?></td>
                                <td class="text-right"><?php if ($sort == 'c.discount') { ?>
                                    <a href="<?php echo $sort_discount; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_discount; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_discount; ?>"><?php echo $column_discount; ?></a>
                                    <?php } ?></td>
                                <td class="text-left"><?php if ($sort == 'c.date_start') { ?>
                                    <a href="<?php echo $sort_date_start; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_start; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_date_start; ?>"><?php echo $column_date_start; ?></a>
                                    <?php } ?></td>
                                <td class="text-left"><?php if ($sort == 'c.date_end') { ?>
                                    <a href="<?php echo $sort_date_end; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_end; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_date_end; ?>"><?php echo $column_date_end; ?></a>
                                    <?php } ?></td>
                                <td class="text-left"><?php if ($sort == 'c.status') { ?>
                                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                                    <?php } ?></td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ($coupons) { ?>
                            <?php foreach ($coupons as $coupon) { ?>
                            <tr>
                                <td class="text-center"><?php if (in_array($coupon['coupon_id'], $selected)) { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $coupon['coupon_id']; ?>" checked="checked" />
                                    <?php } else { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $coupon['coupon_id']; ?>" />
                                    <?php } ?></td>
                                <td class="text-left">
                                    <a href="<?php echo $coupon['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary btn-sm btn-basic-list"><i class="fa fa-pencil"></i></a>
                                    <?php echo $coupon['name']; ?></td>
                                <td class="text-left"><?php echo $coupon['code']; ?></td>
                                <td class="text-right"><?php echo $coupon['discount']; ?></td>
                                <td class="text-left"><?php echo $coupon['date_start']; ?></td>
                                <td class="text-left"><?php echo $coupon['date_end']; ?></td>
                                <td class="text-left"><?php echo $coupon['status']; ?></td>
                            </tr>
                            <?php } ?>
                            <?php } else { ?>
                            <tr>
                                <td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
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
$('.date').datetimepicker({
    pickTime: false
});
//--></script>
<script type="text/javascript"><!--
$('input[name=\'filter_name\']').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=marketing/coupon/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['name'],
                        value: item['coupon_id']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('input[name=\'filter_name\']').val(item['label']);
    }
});

$('input[name=\'filter_code\']').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=marketing/coupon/autocomplete&token=<?php echo $token; ?>&filter_code=' +  encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['code'],
                        value: item['coupon_id']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('input[name=\'filter_code\']').val(item['label']);
    }
});
//--></script>
<script type="text/javascript"><!--
function filter() {
    var url = 'index.php?route=marketing/coupon&token=<?php echo $token; ?>';

    var filter_name = $('input[name=\'filter_name\']').val();

    if (filter_name) {
        url += '&filter_name=' + encodeURIComponent(filter_name);
    }

    var filter_code = $('input[name=\'filter_code\']').val();

    if (filter_code) {
        url += '&filter_code=' + encodeURIComponent(filter_code);
    }

    var filter_date_start = $('input[name=\'filter_date_start\']').val();

    if (filter_date_start) {
        url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
    }

    var filter_date_end = $('input[name=\'filter_date_end\']').val();

    if (filter_date_end) {
        url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
    }

    var filter_discount = $('input[name=\'filter_discount\']').val();

    if (filter_discount) {
        url += '&filter_discount=' + encodeURIComponent(filter_discount);
    }

    var filter_status = $('select[name=\'filter_status\']').val();

    if (filter_status != '*') {
        url += '&filter_status=' + encodeURIComponent(filter_status);
    }

    location = url;
}
//--></script>
<?php echo $footer; ?>
