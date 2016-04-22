<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-success"><i class="fa fa-plus"></i></a></div>
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
                                    <button type="button" onclick="filter();" class="btn btn-default"><div class="filter-type"><?php echo $entry_order_id; ?></div></button>
                                    <ul class="dropdown-menu">
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_order_id; ?>', 'filter_order_id');"><?php echo $entry_order_id; ?></a></li>
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_customer; ?>', 'filter_customer');"><?php echo $entry_customer; ?></a></li>
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_order_status; ?>', 'filter_order_status');"><?php echo $entry_order_status; ?></a></li>
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_total; ?>', 'filter_total');"><?php echo $entry_total; ?></a></li>
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_date_added; ?>', 'filter_date_added');"><?php echo $entry_date_added; ?></a></li>
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_date_modified; ?>', 'filter_date_modified');"><?php echo $entry_date_modified; ?></a></li>
                                    </ul>
                                </div>
                                <input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>"id="input-order-id" class="form-control filter" />
                                <input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>"id="input-customer" class="form-control hidden filter" />
                                <input type="text" name="filter_total" value="<?php echo $filter_total; ?>" id="input-total" class="form-control hidden filter" />
                                <select name="filter_order_status" id="input-order-status" class="form-control hidden filter">
                                    <option value="*"></option>
                                    <?php if ($filter_order_status == '0') { ?>
                                    <option value="0" selected="selected"><?php echo $text_missing; ?></option>
                                    <?php } else { ?>
                                    <option value="0"><?php echo $text_missing; ?></option>
                                    <?php } ?>
                                    <?php foreach ($order_statuses as $order_status) { ?>
                                    <?php if ($order_status['order_status_id'] == $filter_order_status) { ?>
                                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                    <?php } else { ?>
                                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
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
                    <?php if (!empty($filter_order_id) || !empty($filter_customer) || isset($filter_order_status) || !empty($filter_total) || !empty($filter_date_added) || !empty($filter_date_modified)) { ?>
                    <div class="row">
                        <div class="col-lg-12 filter-tag">
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
                            <?php if ($filter_order_status) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $entry_order_status; ?>:</label> 
                                <label class="filter-label"> 
                                    <?php if ($filter_order_status == '0') { ?>
                                    <?php echo $text_missing; ?>
                                    <?php } ?>
                                    <?php foreach ($order_statuses as $order_status) { ?>
                                    <?php if ($order_status['order_status_id'] == $filter_order_status) { ?>
                                    <?php echo $order_status['name']; ?>
                                    <?php } ?>
                                    <?php } ?>
                                </label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_order_status');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                            <?php if ($filter_total) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $entry_total; ?>:</label> <label class="filter-label"> <?php echo $filter_total; ?></label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_total');"><i class="fa fa-times"></i></a>
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
                <form method="post" action="" enctype="multipart/form-data" target="_blank" id="form-order">
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
                                          <li><a id="button-shipping" form="form-order" formaction="<?php echo $shipping; ?>" ><i class="fa fa-truck"></i> <?php echo $button_shipping_print; ?></a></li>
                                          <li><a id="button-invoice" form="form-order" formaction="<?php echo $invoice; ?>" ><i class="fa fa-print"></i> <?php echo $button_invoice_print; ?></a></li>
                                          <li><a id="button-invoicepdf" form="form-order" formaction="<?php echo $invoicepdf; ?>" ><i class="fa fa-file-pdf-o"></i> <?php echo $button_invoice_pdf; ?></a></li>
                                      </ul>
                                    </span>
                                    </div></td>
                                <td class="text-right"><?php if ($sort == 'o.order_id') { ?>
                                    <a href="<?php echo $sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_order_id; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_order; ?>"><?php echo $column_order_id; ?></a>
                                    <?php } ?></td>
                                <td class="text-left"><?php if ($sort == 'customer') { ?>
                                    <a href="<?php echo $sort_customer; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_customer; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_customer; ?>"><?php echo $column_customer; ?></a>
                                    <?php } ?></td>
                                <td class="text-left"><?php if ($sort == 'status') { ?>
                                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                                    <?php } ?></td>
                                <td class="text-right"><?php if ($sort == 'o.total') { ?>
                                    <a href="<?php echo $sort_total; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_total; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_total; ?>"><?php echo $column_total; ?></a>
                                    <?php } ?></td>
                                <td class="text-left"><?php if ($sort == 'o.date_added') { ?>
                                    <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                                    <?php } ?></td>
                                <td class="text-left"><?php if ($sort == 'o.date_modified') { ?>
                                    <a href="<?php echo $sort_date_modified; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_modified; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_date_modified; ?>"><?php echo $column_date_modified; ?></a>
                                    <?php } ?></td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ($orders) { ?>
                            <?php foreach ($orders as $order) { ?>
                            <tr>
                                <td class="text-center"><?php if (in_array($order['order_id'], $selected)) { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" checked="checked" />
                                    <?php } else { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" />
                                    <?php } ?>
                                    <input type="hidden" name="shipping_code[]" value="<?php echo $order['shipping_code']; ?>" /></td>
                                <td class="text-right"><?php echo $order['order_id']; ?></td>
                                <td class="text-left">
                                    <a href="<?php echo $order['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info btn-sm btn-basic-list"><i class="fa fa-eye"></i></a>
                                    <a href="<?php echo $order['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary btn-sm btn-basic-list"><i class="fa fa-pencil"></i></a>
                                    <a href="<?php echo $order['delete']; ?>" id="button-delete<?php echo $order['order_id']; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger btn-sm btn-basic-list"><i class="fa fa-trash-o"></i></a>
                                    <?php echo $order['customer']; ?>
                                </td>
                                <td class="text-left"><?php echo $order['status']; ?></td>
                                <td class="text-right"><?php echo $order['total']; ?></td>
                                <td class="text-left"><?php echo $order['date_added']; ?></td>
                                <td class="text-left"><?php echo $order['date_modified']; ?></td>
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

    $('input[name^=\'selected\']').on('change', function() {
        $('#button-shipping, #button-invoice, #button-invoicepdf').prop('disabled', true);

        var selected = $('input[name^=\'selected\']:checked');

        if (selected.length) {
            $('#button-invoice').prop('disabled', false);
            $('#button-invoicepdf').prop('disabled', false);
        }

        for (i = 0; i < selected.length; i++) {
            if ($(selected[i]).parent().find('input[name^=\'shipping_code\']').val()) {
                $('#button-shipping').prop('disabled', false);

                break;
            }
        }
    });

    // IE and Edge fix!
    $('#button-shipping, #button-invoice, #button-invoicepdf').on('click', function(e) {
        $('#form-order').attr('action', this.getAttribute('formAction'));

        $('#form-order').submit();
    });

    $('input[name^=\'selected\']:first').trigger('change');

    $('a[id^=\'button-delete\']').on('click', function(e) {
        e.preventDefault();

        confirmItemSetLink('<?php echo $text_confirm_title; ?>', '<?php echo $text_confirm; ?>', $(this).attr('href'));
    });
    //--></script>
        <script type="text/javascript"><!--
    function filter() {
        url = 'index.php?route=sale/order&token=<?php echo $token; ?>';

        var filter_order_id = $('input[name=\'filter_order_id\']').val();

        if (filter_order_id) {
            url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
        }

        var filter_customer = $('input[name=\'filter_customer\']').val();

        if (filter_customer) {
            url += '&filter_customer=' + encodeURIComponent(filter_customer);
        }

        var filter_order_status = $('select[name=\'filter_order_status\']').val();

        if (filter_order_status != '*') {
            url += '&filter_order_status=' + encodeURIComponent(filter_order_status);
        }

        var filter_total = $('input[name=\'filter_total\']').val();

        if (filter_total) {
            url += '&filter_total=' + encodeURIComponent(filter_total);
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
    <script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
    <link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
    <script type="text/javascript"><!--
    $('.date').datetimepicker({
        pickTime: false
    });
    //--></script></div>
<?php echo $footer; ?>
