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
                                    <button type="button" onclick="filter();" class="btn btn-default"><div class="filter-type"><?php echo $entry_return_id; ?></div></button>
                                    <ul class="dropdown-menu">
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_return_id; ?>', 'filter_return_id');"><?php echo $entry_return_id; ?></a></li>
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_order_id; ?>', 'filter_order_id');"><?php echo $entry_order_id; ?></a></li>
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_customer; ?>', 'filter_customer');"><?php echo $entry_customer; ?></a></li>
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_product; ?>', 'filter_product');"><?php echo $entry_product; ?></a></li>
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_model; ?>', 'filter_model');"><?php echo $entry_model; ?></a></li>
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_return_status; ?>', 'filter_return_status_id');"><?php echo $entry_return_status; ?></a></li>
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_date_added; ?>', 'filter_date_added');"><?php echo $entry_date_added; ?></a></li>
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_date_modified; ?>', 'filter_date_modified');"><?php echo $entry_date_modified; ?></a></li>
                                    </ul>
                                </div>
                                <input type="text" name="filter_return_id" value="<?php echo $filter_return_id; ?>"id="input-return-id" class="form-control filter" />
                                <input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>"id="input-order-id" class="form-control hidden filter" />
                                <input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>"id="input-customer" class="form-control hidden filter" />
                                <input type="text" name="filter_product" value="<?php echo $filter_product; ?>"id="input-customer" class="form-control hidden filter" />
                                <input type="text" name="filter_model" value="<?php echo $filter_model; ?>"id="input-customer" class="form-control hidden filter" />
                                <select name="filter_return_status_id" id="input-return-status" class="form-control hidden filter">
                                    <option value="*"></option>
                                    <?php foreach ($return_statuses as $return_status) { ?>
                                    <?php if ($return_status['return_status_id'] == $filter_return_status_id) { ?>
                                    <option value="<?php echo $return_status['return_status_id']; ?>" selected="selected"><?php echo $return_status['name']; ?></option>
                                    <?php } else { ?>
                                    <option value="<?php echo $return_status['return_status_id']; ?>"><?php echo $return_status['name']; ?></option>
                                    <?php } ?>
                                    <?php } ?>
                                </select>
                                <div class="input-group date hidden filter filter_date_added">
                                  <input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>"data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control hidden filter" />
                                  <span class="input-group-btn">
                                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                  </span></div>
                                <div class="input-group date hidden filter filter_date_modified">
                                  <input type="text" name="filter_date_modified" value="<?php echo $filter_date_modified; ?>"  data-date-format="YYYY-MM-DD" id="input-date-modified" class="form-control hidden filter" />
                                  <span class="input-group-btn">
                                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                  </span></div>
                            </div>
                        </div>
                    </div>
                    <?php if (!empty($filter_return_id) || !empty($filter_order_id) || !empty($filter_customer) || !empty($filter_product) || !empty($filter_model) || isset($filter_return_status_id) || !empty($filter_date_added) || !empty($filter_date_modified)) { ?>
                    <div class="row">
                        <div class="col-lg-12 filter-tag">
                            <?php if ($filter_return_id) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $entry_return_id; ?>:</label> <label class="filter-label"> <?php echo $filter_return_id; ?></label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_return_id');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                            <?php if ($filter_order_id) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $entry_order_id; ?>:</label> <label class="filter-label"> <?php echo $filter_order_id; ?></label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_order_id');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                            <?php if ($filter_customer) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $entry_customer; ?>:</label> <label class="filter-label"> <?php echo $filter_customer; ?></label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_customer');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                            <?php if ($filter_product) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $entry_product; ?>:</label> <label class="filter-label"> <?php echo $filter_product; ?></label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_product');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                            <?php if ($filter_model) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $entry_model; ?>:</label> <label class="filter-label"> <?php echo $filter_model; ?></label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_model');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                            <?php if ($filter_return_status_id) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $entry_return_status; ?>:</label> 
                                <label class="filter-label"> 
                                <?php foreach ($return_statuses as $return_status) { ?>
                                    <?php if ($return_status['return_status_id'] == $filter_return_status_id) { ?>
                                    <?php echo $return_status['name']; ?>
                                    <?php } ?>
                                <?php } ?>
                                </label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_return_status_id');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                            <?php if ($filter_date_added) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $entry_date_added; ?>:</label> <label class="filter-label"> <?php echo $filter_date_added; ?></label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_date_added');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                            <?php if ($filter_date_modified) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $entry_date_modified; ?>:</label> <label class="filter-label"> <?php echo $filter_date_modified; ?></label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_date_modified');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-return">
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
                                              <li><a onclick="confirmItem('<?php echo $text_confirm_title; ?>', '<?php echo $text_confirm; ?>');"><i class="fa fa-trash-o"></i> <?php echo $button_delete; ?></a></li>
                                          </ul>
                                        </span>
                                    </div></td>
                                <td class="text-right"><?php if ($sort == 'r.return_id') { ?>
                                    <a href="<?php echo $sort_return_id; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_return_id; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_return_id; ?>"><?php echo $column_return_id; ?></a>
                                    <?php } ?></td>
                                <td class="text-right"><?php if ($sort == 'r.order_id') { ?>
                                    <a href="<?php echo $sort_order_id; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_order_id; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_order_id; ?>"><?php echo $column_order_id; ?></a>
                                    <?php } ?></td>
                                <td class="text-left"><?php if ($sort == 'customer') { ?>
                                    <a href="<?php echo $sort_customer; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_customer; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_customer; ?>"><?php echo $column_customer; ?></a>
                                    <?php } ?></td>
                                <td class="text-left"><?php if ($sort == 'r.product') { ?>
                                    <a href="<?php echo $sort_product; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_product; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_product; ?>"><?php echo $column_product; ?></a>
                                    <?php } ?></td>
                                <td class="text-left"><?php if ($sort == 'r.model') { ?>
                                    <a href="<?php echo $sort_model; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_model; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_model; ?>"><?php echo $column_model; ?></a>
                                    <?php } ?></td>
                                <td class="text-left"><?php if ($sort == 'status') { ?>
                                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                                    <?php } ?></td>
                                <td class="text-left"><?php if ($sort == 'r.date_added') { ?>
                                    <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                                    <?php } ?></td>
                                <td class="text-left"><?php if ($sort == 'r.date_modified') { ?>
                                    <a href="<?php echo $sort_date_modified; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_modified; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_date_modified; ?>"><?php echo $column_date_modified; ?></a>
                                    <?php } ?></td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ($returns) { ?>
                            <?php foreach ($returns as $return) { ?>
                            <tr>
                                <td class="text-center"><?php if (in_array($return['return_id'], $selected)) { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $return['return_id']; ?>" checked="checked" />
                                    <?php } else { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $return['return_id']; ?>" />
                                    <?php } ?></td>
                                <td class="text-right">
                                    <a href="<?php echo $return['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary btn-sm btn-basic-list"><i class="fa fa-pencil"></i></a>
                                    <?php echo $return['return_id']; ?>
                                </td>
                                <td class="text-right"><?php echo $return['order_id']; ?></td>
                                <td class="text-left"><?php echo $return['customer']; ?></td>
                                <td class="text-left"><?php echo $return['product']; ?></td>
                                <td class="text-left"><?php echo $return['model']; ?></td>
                                <td class="text-left"><?php echo $return['status']; ?></td>
                                <td class="text-left"><?php echo $return['date_added']; ?></td>
                                <td class="text-left"><?php echo $return['date_modified']; ?></td>
                            </tr>
                            <?php } ?>
                            <?php } else { ?>
                            <tr>
                                <td class="text-center" colspan="10"><?php echo $text_no_results; ?></td>
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
    <script type="text/javascript"><!--
    $('input[name=\'filter_customer\']').autocomplete({
        'source': function(request, response) {
            $.ajax({
                url: 'index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                dataType: 'json',
                success: function(json) {
                    response($.map(json, function(item) {
                        return {
                            label: item['name'],
                            value: item['customer_id']
                        }
                    }));
                }
            });
        },
        'select': function(item) {
            $('input[name=\'filter_customer\']').val(item['label']);
            filter();
        }
    });

    $('input[name=\'filter_product\']').autocomplete({
        'source': function(request, response) {
            $.ajax({
                url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
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
            $('input[name=\'filter_product\']').val(item['label']);
            filter();
        }
    });

    $('input[name=\'filter_model\']').autocomplete({
        'source': function(request, response) {
            $.ajax({
                url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_model=' +  encodeURIComponent(request),
                dataType: 'json',
                success: function(json) {
                    response($.map(json, function(item) {
                        return {
                            label: item['model'],
                            value: item['product_id']
                        }
                    }));
                }
            });
        },
        'select': function(item) {
            $('input[name=\'filter_model\']').val(item['label']);
            filter();
        }
    });
    //--></script>
    <script type="text/javascript"><!--
    function filter() {
        url = 'index.php?route=sale/return&token=<?php echo $token; ?>';

        var filter_return_id = $('input[name=\'filter_return_id\']').val();

        if (filter_return_id) {
            url += '&filter_return_id=' + encodeURIComponent(filter_return_id);
        }

        var filter_order_id = $('input[name=\'filter_order_id\']').val();

        if (filter_order_id) {
            url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
        }

        var filter_customer = $('input[name=\'filter_customer\']').val();

        if (filter_customer) {
            url += '&filter_customer=' + encodeURIComponent(filter_customer);
        }

        var filter_product = $('input[name=\'filter_product\']').val();

        if (filter_product) {
            url += '&filter_product=' + encodeURIComponent(filter_product);
        }

        var filter_model = $('input[name=\'filter_model\']').val();

        if (filter_model) {
            url += '&filter_model=' + encodeURIComponent(filter_model);
        }

        var filter_return_status_id = $('select[name=\'filter_return_status_id\']').val();

        if (filter_return_status_id != '*') {
            url += '&filter_return_status_id=' + encodeURIComponent(filter_return_status_id);
        }

        var filter_date_added = $('input[name=\'filter_date_added\']').val();

        if (filter_date_added) {
            url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
        }

        var filter_date_modified = $('input[name=\'filter_date_modified\']').val();

        if (filter_date_modified) {
            url += '&filter_date_modified=' + encodeURIComponent(filter_date_modified);
        }

        location = url;
    }
    //--></script>
    <script type="text/javascript"><!--
    $('.date').datetimepicker({
        pickTime: false
    });
    //--></script></div>
<?php echo $footer; ?>
