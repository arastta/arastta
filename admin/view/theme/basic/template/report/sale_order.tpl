<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <h1><?php echo $heading_title; ?></h1>
        </div>
    </div>
    <div class="container-fluid">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart"></i> <?php echo $text_list; ?></h3>
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
                                    <button type="button" onclick="filter();" class="btn btn-default"><div class="filter-type"><?php echo $entry_date_start; ?></div></button>
                                    <ul class="dropdown-menu">
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_date_start; ?>', 'filter_date_start');"><?php echo $entry_date_start; ?></a></li>
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_date_end; ?>', 'filter_date_end');"><?php echo $entry_date_end; ?></a></li>
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_group; ?>', 'filter_group');"><?php echo $entry_group; ?></a></li>
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_status; ?>', 'filter_order_status_id');"><?php echo $entry_status; ?></a></li>
                                    </ul>
                                </div>
                                <div class="input-group date filter filter_date_start">
                                    <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control filter" />
                                  <span class="input-group-btn">
                                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                  </span></div>
                                <div class="input-group date filter hidden filter_date_end">
                                    <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control filter hidden" />
                                  <span class="input-group-btn">
                                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                  </span></div>
                                <select name="filter_group" id="input-group" class="form-control filter hidden">
                                    <?php foreach ($groups as $group) { ?>
                                    <?php if ($group['value'] == $filter_group) { ?>
                                    <option value="<?php echo $group['value']; ?>" selected="selected"><?php echo $group['text']; ?></option>
                                    <?php } else { ?>
                                    <option value="<?php echo $group['value']; ?>"><?php echo $group['text']; ?></option>
                                    <?php } ?>
                                    <?php } ?>
                                </select>
                                <select name="filter_order_status_id" id="input-status" class="form-control filter hidden">
                                    <option value="0"><?php echo $text_all_status; ?></option>
                                    <?php foreach ($order_statuses as $order_status) { ?>
                                    <?php if ($order_status['order_status_id'] == $filter_order_status_id) { ?>
                                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                    <?php } else { ?>
                                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                    <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <?php if (!empty($filter_date_start) || !empty($filter_date_end) || !empty($filter_group) || isset($filter_order_status_id)) { ?>
                    <div class="row">
                        <div class="col-lg-12 filter-tag">
                            <?php if ($filter_date_start) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $entry_date_start; ?>:</label> <label class="filter-label"> <?php echo $filter_date_start; ?></label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_date_start');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                            <?php if ($filter_date_end) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $entry_date_end; ?>:</label> <label class="filter-label"> <?php echo $filter_date_end; ?></label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_date_end');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                            <?php if ($filter_group) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $entry_group; ?>:</label> <label class="filter-label"> <?php echo $filter_group; ?></label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_group');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                            <?php if ($filter_order_status_id) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $entry_status; ?>:</label> 
                                <label class="filter-label"> 
                                <?php foreach ($order_statuses as $order_status) { ?>
                                <?php if ($order_status['order_status_id'] == $filter_order_status_id) { ?>
                                <?php echo $order_status['name']; ?>
                                <?php } ?>
                                <?php } ?>
                                </label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_order_status_id');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <td class="text-left"><?php echo $column_date_start; ?></td>
                            <td class="text-left"><?php echo $column_date_end; ?></td>
                            <td class="text-right"><?php echo $column_orders; ?></td>
                            <td class="text-right"><?php echo $column_products; ?></td>
                            <td class="text-right"><?php echo $column_tax; ?></td>
                            <td class="text-right"><?php echo $column_total; ?></td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if ($orders) { ?>
                        <?php foreach ($orders as $order) { ?>
                        <tr>
                            <td class="text-left"><?php echo $order['date_start']; ?></td>
                            <td class="text-left"><?php echo $order['date_end']; ?></td>
                            <td class="text-right"><?php echo $order['orders']; ?></td>
                            <td class="text-right"><?php echo $order['products']; ?></td>
                            <td class="text-right"><?php echo $order['tax']; ?></td>
                            <td class="text-right"><?php echo $order['total']; ?></td>
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
                <div class="row">
                    <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
                    <div class="col-sm-6 text-right"><?php echo $results; ?></div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript"><!--
    function filter() {
        url = 'index.php?route=report/sale_order&token=<?php echo $token; ?>';

        var filter_date_start = $('input[name=\'filter_date_start\']').val();

        if (filter_date_start) {
            url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
        }

        var filter_date_end = $('input[name=\'filter_date_end\']').val();

        if (filter_date_end) {
            url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
        }

        var filter_group = $('select[name=\'filter_group\']').val();

        if (filter_group) {
            url += '&filter_group=' + encodeURIComponent(filter_group);
        }

        var filter_order_status_id = $('select[name=\'filter_order_status_id\']').val();

        if (filter_order_status_id != 0) {
            url += '&filter_order_status_id=' + encodeURIComponent(filter_order_status_id);
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
