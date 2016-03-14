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
                                    <button type="button" onclick="filter();" class="btn btn-default"><div class="filter-type"><?php echo $entry_product; ?></div></button>
                                    <ul class="dropdown-menu">
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_product; ?>', 'filter_product');"><?php echo $entry_product; ?></a></li>
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_author; ?>', 'filter_author');"><?php echo $entry_author; ?></a></li>
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_status; ?>', 'filter_status');"><?php echo $entry_status; ?></a></li>
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_date_added; ?>', 'filter_date_added');"><?php echo $entry_date_added; ?></a></li>
                                    </ul>
                                </div>
                                <input type="text" name="filter_product"  value="<?php echo $filter_product; ?>" id="input-product" class="form-control filter">
                                <input type="text" name="filter_author" value="<?php echo $filter_author; ?>" id="input-author" class="form-control hidden filter" />
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
                                <div class="input-group date filter hidden filter_date_added">
                                  <input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="<?php echo $entry_date_added; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control filter hidden" />
                                  <span class="input-group-btn">
                                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                  </span></div>
                                </span>
                            </div>
                        </div>
                    </div>
                    <?php if (!empty($filter_product) || !empty($filter_author) || isset($filter_status) || !empty($filter_date_added)) { ?>
                    <div class="row">
                        <div class="col-lg-12 filter-tag">
                            <?php if ($filter_product) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $entry_product; ?>:</label> <label class="filter-label"> <?php echo $filter_product; ?></label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_product');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                            <?php if ($filter_author) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $entry_author; ?>:</label> <label class="filter-label"> <?php echo $filter_author; ?></label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_author');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                            <?php if (isset($filter_status)) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $column_status; ?>:</label> <label class="filter-label"> <?php echo ($filter_status) ? $text_enabled : $text_disabled; ?></label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_status');"><i class="fa fa-times"></i></a>
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
                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-review">
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
                                <td class="text-left"><?php if ($sort == 'pd.name') { ?>
                                    <a href="<?php echo $sort_product; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_product; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_product; ?>"><?php echo $column_product; ?></a>
                                    <?php } ?></td>
                                <td class="text-left"><?php if ($sort == 'r.author') { ?>
                                    <a href="<?php echo $sort_author; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_author; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_author; ?>"><?php echo $column_author; ?></a>
                                    <?php } ?></td>
                                <td class="text-right"><?php if ($sort == 'r.rating') { ?>
                                    <a href="<?php echo $sort_rating; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_rating; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_rating; ?>"><?php echo $column_rating; ?></a>
                                    <?php } ?></td>
                                <td class="text-left"><?php if ($sort == 'r.status') { ?>
                                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                                    <?php } ?></td>
                                <td class="text-left"><?php if ($sort == 'r.date_added') { ?>
                                    <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                                    <?php } ?></td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ($reviews) { ?>
                            <?php foreach ($reviews as $review) { ?>
                            <tr>
                                <td class="text-center"><?php if (in_array($review['review_id'], $selected)) { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $review['review_id']; ?>" checked="checked" />
                                    <?php } else { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $review['review_id']; ?>" />
                                    <?php } ?></td>
                                <td class="text-left">
                                    <a href="<?php echo $review['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary btn-sm btn-basic-list"><i class="fa fa-pencil"></i></a>
                                    <?php echo $review['name']; ?>
                                </td>
                                <td class="text-left">
                                    <span class="review-author"><?php echo $review['author']; ?></span>
                                </td>
                                <td class="text-right">
                                    <span class="review-rating"><?php echo $review['rating']; ?></span>
                                </td>
                                <td class="text-left">
                                    <span class="review-status"data-prepend="<?php echo $text_select; ?>" data-source="{'1': '<?php echo $text_enabled; ?>', '0': '<?php echo $text_disabled; ?>'}"><?php echo $review['status']; ?></span>
                                </td>
                                <td class="text-left">
                                    <span class="review-date-added" id="dob" data-format="dd/mm/yyyy"><?php echo $review['date_added']; ?></span>
                                </td>
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
    $(document).ready(function() {
        $.fn.editable.defaults.mode = 'inline';

        $('.review-author').editable({
            url: function (params) {
                $.ajax({
                    type: 'post',
                    url: 'index.php?route=catalog/review/inline&token=<?php echo $token; ?>&review_id=' + $(this).parent().parent().find( "input[name*=\'selected\']").val(),
                    data: {author: params.value},
                    async: false,
                    error: function (xhr, ajaxOptions, thrownError) {
                        return false;
                    }
                })
            },
            showbuttons: false,
        });

        $('.review-rating').editable({
            url: function (params) {
                $.ajax({
                    type: 'post',
                    url: 'index.php?route=catalog/review/inline&token=<?php echo $token; ?>&review_id=' + $(this).parent().parent().find( "input[name*=\'selected\']").val(),
                    data: {rating: params.value},
                    async: false,
                    error: function (xhr, ajaxOptions, thrownError) {
                        return false;
                    }
                })
            },
            showbuttons: false,
        });

        $('.review-status').editable({
            type: 'select',
            url: function (params) {
                $.ajax({
                    type: 'post',
                    url: 'index.php?route=catalog/review/inline&token=<?php echo $token; ?>&review_id=' + $(this).parent().parent().find( "input[name*=\'selected\']").val(),
                    data: {status: params.value},
                    async: false,
                    error: function (xhr, ajaxOptions, thrownError) {
                        return false;
                    }
                })
            },
            showbuttons: false,
        });

        $('.review-date-added').editable({
            type:  'date',
            pk:    1,
            name:  'dob',
            url: function (params) {
                $.ajax({
                    type: 'post',
                    url: 'index.php?route=catalog/review/inline&token=<?php echo $token; ?>&review_id=' + $(this).parent().parent().find( "input[name*=\'selected\']").val(),
                    data: {date_added: params.value},
                    async: false,
                    error: function (xhr, ajaxOptions, thrownError) {
                        return false;
                    }
                })
            },
            showbuttons: false,
        });
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
    //--></script>
    <script type="text/javascript"><!--
    $('.date').datetimepicker({
        pickTime: false
    });
    //--></script></div>
<script type="text/javascript"><!--
function filter() {
    url = 'index.php?route=catalog/review&token=<?php echo $token; ?>';

    var filter_product = $('input[name=\'filter_product\']').val();

    if (filter_product) {
        url += '&filter_product=' + encodeURIComponent(filter_product);
    }

    var filter_author = $('input[name=\'filter_author\']').val();

    if (filter_author) {
        url += '&filter_author=' + encodeURIComponent(filter_author);
    }

    var filter_status = $('select[name=\'filter_status\']').val();

    if (filter_status != '*') {
        url += '&filter_status=' + encodeURIComponent(filter_status);
    }

    var filter_date_added = $('input[name=\'filter_date_added\']').val();

    if (filter_date_added) {
        url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
    }

    location = url;
};
//--></script>
<?php echo $footer; ?>
