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
                                    <button type="button" onclick="filter();" class="btn btn-default"><div class="filter-type"><?php echo $entry_name; ?></div></button>
                                    <ul class="dropdown-menu">
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_name; ?>', 'filter_name');"><?php echo $entry_name; ?></a></li>
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_email; ?>', 'filter_email');"><?php echo $entry_email; ?></a></li>
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_customer_group; ?>', 'filter_customer_group_id');"><?php echo $entry_customer_group; ?></a></li>
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_status; ?>', 'filter_status');"><?php echo $entry_status; ?></a></li>
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_approved; ?>', 'filter_approved');"><?php echo $entry_approved; ?></a></li>
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_ip; ?>', 'filter_ip');"><?php echo $entry_ip; ?></a></li>
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_date_added; ?>', 'filter_date_added');"><?php echo $entry_date_added; ?></a></li>
                                    </ul>
                                </div>
                                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control filter" />
                                <input type="text" name="filter_email" value="<?php echo $filter_email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control hidden filter" />
                                <select name="filter_customer_group_id" id="input-customer-group" class="form-control hidden filter">
                                    <option value="*"></option>
                                    <?php foreach ($customer_groups as $customer_group) { ?>
                                    <?php if ($customer_group['customer_group_id'] == $filter_customer_group_id) { ?>
                                    <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                                    <?php } else { ?>
                                    <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                                    <?php } ?>
                                    <?php } ?>
                                </select>
                                <select name="filter_status" id="input-status" class="form-control hidden filter">
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
                                <select name="filter_approved" id="input-approved" class="form-control hidden filter">
                                    <option value="*"></option>
                                    <?php if ($filter_approved) { ?>
                                    <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                                    <?php } else { ?>
                                    <option value="1"><?php echo $text_yes; ?></option>
                                    <?php } ?>
                                    <?php if (!$filter_approved && !is_null($filter_approved)) { ?>
                                    <option value="0" selected="selected"><?php echo $text_no; ?></option>
                                    <?php } else { ?>
                                    <option value="0"><?php echo $text_no; ?></option>
                                    <?php } ?>
                                </select>
                                <input type="text" name="filter_ip" value="<?php echo $filter_ip; ?>" placeholder="<?php echo $entry_ip; ?>" id="input-ip" class="form-control hidden filter" />
                                <div class="input-group date hidden filter filter_date_added">
                                  <input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>"  data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control hidden filter" />
                                  <span class="input-group-btn">
                                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                  </span></div>
                            </div>
                        </div>
                    </div>
                    <?php if (!empty($filter_name) || !empty($filter_email) || !empty($filter_customer_group_id) || isset($filter_status) || !empty($filter_approved) || !empty($filter_date_added)) { ?>
                    <div class="row">
                        <div class="col-lg-12 filter-tag">
                            <?php if ($filter_name) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $entry_name; ?>:</label> <label class="filter-label"> <?php echo $filter_name; ?></label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_name');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                            <?php if ($filter_email) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $entry_email; ?>:</label> <label class="filter-label"> <?php echo $filter_email; ?></label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_email');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                            <?php if ($filter_customer_group_id) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $entry_date_start; ?>:</label> 
                                <label class="filter-label"> 
                                <?php foreach ($customer_groups as $customer_group) { ?>
                                    <?php if ($customer_group['customer_group_id'] == $filter_customer_group_id) { ?>
                                    <?php echo $customer_group['name']; ?>
                                    <?php } ?>
                                <?php } ?>
                                </label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_customer_group_id');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                            <?php if ($filter_status) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $entry_status; ?>:</label> <label class="filter-label"> <?php echo ($filter_status) ? $text_enabled : $text_disabled; ?></label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_status');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                            <?php if ($filter_approved) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $entry_approved; ?>:</label> <label class="filter-label"> <?php echo ($filter_approved) ? $text_yes : $text_no; ?></label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_approved');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                            <?php if ($filter_date_added) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $entry_date_added; ?>:</label> <label class="filter-label"> <?php echo $filter_date_added; ?></label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_date_added');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-customer">
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
                                <td class="text-left"><?php if ($sort == 'c.email') { ?>
                                    <a href="<?php echo $sort_email; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_email; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_email; ?>"><?php echo $column_email; ?></a>
                                    <?php } ?></td>
                                <td class="text-left"><?php if ($sort == 'customer_group') { ?>
                                    <a href="<?php echo $sort_customer_group; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_customer_group; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_customer_group; ?>"><?php echo $column_customer_group; ?></a>
                                    <?php } ?></td>
                                <td class="text-left"><?php if ($sort == 'c.status') { ?>
                                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                                    <?php } ?></td>
                                <td class="text-left"><?php if ($sort == 'c.ip') { ?>
                                    <a href="<?php echo $sort_ip; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_ip; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_ip; ?>"><?php echo $column_ip; ?></a>
                                    <?php } ?></td>
                                <td class="text-left"><?php if ($sort == 'c.date_added') { ?>
                                    <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                                    <?php } ?></td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ($customers) { ?>
                            <?php foreach ($customers as $customer) { ?>
                            <tr>
                                <td class="text-center"><?php if (in_array($customer['customer_id'], $selected)) { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $customer['customer_id']; ?>" checked="checked" />
                                    <?php } else { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $customer['customer_id']; ?>" />
                                    <?php } ?></td>
                                <td class="text-left">
                                    <?php if ($customer['approve']) { ?>
                                    <a href="<?php echo $customer['approve']; ?>" data-toggle="tooltip" title="<?php echo $button_approve; ?>" class="btn btn-success btn-sm btn-basic-list"><i class="fa fa-thumbs-o-up"></i></a>
                                    <?php } else { ?>
                                    <button type="button" class="btn btn-success btn-sm btn-basic-list" disabled><i class="fa fa-thumbs-o-up"></i></button>
                                    <?php } ?>
                                    <div class="btn-group" data-toggle="tooltip" title="<?php echo $button_login; ?>">
                                        <button type="button" data-toggle="dropdown" class="btn btn-info dropdown-toggle btn-sm btn-basic-list"><i class="fa fa-lock"></i></button>
                                        <ul class="dropdown-menu pull-right">
                                            <li><a href="index.php?route=sale/customer/login&token=<?php echo $token; ?>&customer_id=<?php echo $customer['customer_id']; ?>&store_id=0" target="_blank"><?php echo $text_default; ?></a></li>
                                            <?php foreach ($stores as $store) { ?>
                                            <li><a href="index.php?route=sale/customer/login&token=<?php echo $token; ?>&customer_id=<?php echo $customer['customer_id']; ?>&store_id=<?php echo $store['store_id']; ?>" target="_blank"><?php echo $store['name']; ?></a></li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                    <?php if ($customer['unlock']) { ?>
                                    <a href="<?php echo $customer['unlock']; ?>" data-toggle="tooltip" title="<?php echo $button_unlock; ?>" class="btn btn-warning btn-sm btn-basic-list"><i class="fa fa-unlock"></i></a>
                                    <?php } else { ?>
                                    <button type="button" class="btn btn-warning btn-sm btn-basic-list" disabled><i class="fa fa-unlock"></i></button>
                                    <?php } ?>
                                    <a href="<?php echo $customer['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary btn-sm btn-basic-list"><i class="fa fa-pencil"></i></a>
                                    <?php echo $customer['name']; ?></td>
                                <td class="text-left"><?php echo $customer['email']; ?></td>
                                <td class="text-left"><?php echo $customer['customer_group']; ?></td>
                                <td class="text-left"><?php echo $customer['status']; ?></td>
                                <td class="text-left"><?php echo $customer['ip']; ?></td>
                                <td class="text-left"><?php echo $customer['date_added']; ?></td>
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
    <script type="text/javascript"><!--
    $('input[name=\'filter_name\']').autocomplete({
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
            $('input[name=\'filter_name\']').val(item['label']);
            filter();
        }
    });

    $('input[name=\'filter_email\']').autocomplete({
        'source': function(request, response) {
            $.ajax({
                url: 'index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>&filter_email=' +  encodeURIComponent(request),
                dataType: 'json',
                success: function(json) {
                    response($.map(json, function(item) {
                        return {
                            label: item['email'],
                            value: item['customer_id']
                        }
                    }));
                }
            });
        },
        'select': function(item) {
            $('input[name=\'filter_email\']').val(item['label']);
            filter();
        }
    });
    //--></script>
    <script type="text/javascript"><!--
    function filter() {
        url = 'index.php?route=sale/customer&token=<?php echo $token; ?>';

        var filter_name = $('input[name=\'filter_name\']').val();

        if (filter_name) {
            url += '&filter_name=' + encodeURIComponent(filter_name);
        }

        var filter_email = $('input[name=\'filter_email\']').val();

        if (filter_email) {
            url += '&filter_email=' + encodeURIComponent(filter_email);
        }

        var filter_customer_group_id = $('select[name=\'filter_customer_group_id\']').val();

        if (filter_customer_group_id != '*') {
            url += '&filter_customer_group_id=' + encodeURIComponent(filter_customer_group_id);
        }

        var filter_status = $('select[name=\'filter_status\']').val();

        if (filter_status != '*') {
            url += '&filter_status=' + encodeURIComponent(filter_status);
        }

        var filter_approved = $('select[name=\'filter_approved\']').val();

        if (filter_approved != '*') {
            url += '&filter_approved=' + encodeURIComponent(filter_approved);
        }

        var filter_ip = $('input[name=\'filter_ip\']').val();

        if (filter_ip) {
            url += '&filter_ip=' + encodeURIComponent(filter_ip);
        }

        var filter_date_added = $('input[name=\'filter_date_added\']').val();

        if (filter_date_added) {
            url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
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
