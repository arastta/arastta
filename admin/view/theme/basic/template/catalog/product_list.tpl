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
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_model; ?>', 'filter_model');"><?php echo $entry_model; ?></a></li>
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $column_category; ?>', 'filter_category');"><?php echo $column_category; ?></a></li>
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_price; ?>', 'filter_price');"><?php echo $entry_price; ?></a></li>
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_quantity; ?>', 'filter_quantity');"><?php echo $entry_quantity; ?></a></li>
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_status; ?>', 'filter_status');"><?php echo $entry_status; ?></a></li>
                                    </ul>
                                </div>
                                <input type="text" name="filter_model"  value="<?php echo $filter_model; ?>" placeholder="<?php echo $entry_model; ?>" id="input-model" class="form-control filter hidden">
                                <input type="text" name="filter_name"  value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control filter">
                                <select name="filter_category" id="input-category" class="form-control filter hidden">
                                    <option value="*"></option>
                                    <?php foreach ($categories as $category) { ?>
                                    <?php if ($category['category_id'] == $filter_category) { ?>
                                    <option value="<?php echo $category['category_id']; ?>" selected="selected"><?php echo $category['name']; ?></option>
                                    <?php } else { ?>
                                    <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
                                    <?php } ?>
                                    <?php } ?>
                                </select>
                                <input type="text" name="filter_price" value="<?php echo $filter_price; ?>" placeholder="<?php echo $entry_price; ?>" id="input-price" class="form-control filter hidden" />
                                <input type="text" name="filter_quantity" value="<?php echo $filter_quantity; ?>" placeholder="<?php echo $entry_quantity; ?>" id="input-quantity" class="form-control filter hidden" />
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
                    <?php if (!empty($filter_name) || !empty($filter_model) || !empty($filter_category) || !empty($filter_price) || !empty($filter_quantity) || isset($filter_status)) { ?>
                    <div class="row">
                        <div class="col-lg-12 filter-tag">
                            <?php if ($filter_name) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $entry_name; ?>:</label> <label class="filter-label"> <?php echo $filter_name; ?></label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_name');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                            <?php if ($filter_model) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $entry_model; ?>:</label> <label class="filter-label"> <?php echo $filter_model; ?></label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_model');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                            <?php if ($filter_category) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $column_category; ?>:</label>
                                <label class="filter-label">
                                    <?php foreach ($categories as $category) { ?>
                                    <?php if ($category['category_id'] == $filter_category) { ?>
                                    <?php echo $category['name']; ?>
                                    <?php } ?>
                                    <?php } ?>
                                </label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_category');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                            <?php if ($filter_price) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $entry_price; ?>:</label> <label class="filter-label"> <?php echo $filter_price; ?></label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_price');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                            <?php if ($filter_quantity) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $entry_quantity; ?>:</label> <label class="filter-label"> <?php echo $filter_quantity; ?></label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_quantity');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                            <?php if (isset($filter_status)) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $entry_status; ?>:</label> <label class="filter-label"> <?php echo ($filter_status) ? $text_enabled : $text_disabled; ?></label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_status');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-product">
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
                                <td class="text-center"><?php echo $column_image; ?></td>
                                <td class="text-left"><?php if ($sort == 'pd.name') { ?>
                                    <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                                    <?php } ?></td>
                                <td class="text-left"><?php if ($sort == 'p.price') { ?>
                                    <a href="<?php echo $sort_price; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_price; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_price; ?>"><?php echo $column_price; ?></a>
                                    <?php } ?></td>
                                <td class="text-right"><?php if ($sort == 'p.quantity') { ?>
                                    <a href="<?php echo $sort_quantity; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_quantity; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_quantity; ?>"><?php echo $column_quantity; ?></a>
                                    <?php } ?></td>
                                <td class="text-left"><?php if ($sort == 'p.status') { ?>
                                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                                    <?php } ?></td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ($products) { ?>
                            <?php foreach ($products as $product) { ?>
                            <tr>
                                <td class="text-center"><?php if (in_array($product['product_id'], $selected)) { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" checked="checked" />
                                    <?php } else { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" />
                                    <?php } ?></td>
                                <td class="text-center">
                                    <a href="" id="thumb-image-<?php echo $product['product_id']; ?>" data-toggle="thumb-image">
                                        <?php if ($product['image']) { ?>
                                        <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="img-thumbnail bulk-action-image" />
                                        <?php } else { ?>
                                        <span class="img-thumbnail list bulk-action-image"><i class="fa fa-camera fa-2x"></i></span>
                                        <?php } ?>
                                    </a>
                                    <input type="hidden" name="image" value="<?php echo $product['image']; ?>" id="input-image-<?php echo $product['product_id']; ?>" /></td>
                                <td class="text-left">
                                    <a href="<?php echo $product['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary btn-sm btn-basic-list"><i class="fa fa-pencil"></i></a>
                                      <span class="product-name" id="name[<?php echo $product['product_id']; ?>]">
                                        <?php echo $product['name']; ?>
                                      </span></td>
                                <td class="text-left"><?php if ($product['special']) { ?>
                                    <span class="product-price" style="text-decoration: line-through;"><?php echo $product['price']; ?></span><br/>
                                    <div class="text-danger"><span class="product-special"><?php echo $product['special']; ?></span></div>
                                    <?php } else { ?>
                                    <span class="product-price">
                                      <?php echo $product['price']; ?>
                                    </span>
                                    <?php } ?></td>
                                <td class="text-right"><?php if ($product['quantity'] <= 0) { ?>
                                    <span class="label label-warning"><span class="product-quantity"><?php echo $product['quantity']; ?></span></span>
                                    <?php } elseif ($product['quantity'] <= 5) { ?>
                                    <span class="label label-danger"><span class="product-quantity"><?php echo $product['quantity']; ?></span></span>
                                    <?php } else { ?>
                                    <span class="label label-success"><span class="product-quantity"><?php echo $product['quantity']; ?></span></span>
                                    <?php } ?></td>
                                <td class="text-left">
                                    <span class="product-status" data-prepend="<?php echo $text_select; ?>" data-source="{'1': '<?php echo $text_enabled; ?>', '0': '<?php echo $text_disabled; ?>'}"><?php echo $product['status']; ?></span>
                                </td>
                            </tr>
                            <?php } ?>
                            <?php } else { ?>
                            <tr>
                                <td class="text-center" colspan="9"><?php echo $text_no_results; ?></td>
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
    // Image Manager
    $(document).delegate('a[data-toggle=\'thumb-image\']', 'click', function(e) {
        e.preventDefault();

        $('.popover').popover('hide', function() {
            $('.popover').remove();
        });

        var element = this;
        $(element).popover({
            html: true,
            placement: 'right',
            trigger: 'manual',
            content: function() {
                return '<button type="button" id="button-image" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></button> <button type="button" id="button-clear" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i></button>';
            }
        });

        $(element).popover('toggle');

        $('#button-image').on('click', function() {
            $('#modal-image').remove();

            $.ajax({
                url: 'index.php?route=common/filemanager&token=' + getURLVar('token') + '&target=' + $(element).parent().find('input').attr('id') + '&thumb=' + $(element).attr('id'),
                dataType: 'html',
                beforeSend: function() {
                    $('#button-image i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
                    $('#button-image').prop('disabled', true);
                },
                complete: function() {
                    $('#button-image i').replaceWith('<i class="fa fa-upload"></i>');
                    $('#button-image').prop('disabled', false);
                },
                success: function(html) {
                    $('body').append('<div id="modal-image" class="modal">' + html + '</div>');

                    $('#modal-image').modal('show');
                }
            });

            $(element).popover('hide', function() {
                $('.popover').remove();
            });
        });

        $('#button-clear').on('click', function() {
            $(element).find('img').attr('src', $(element).find('img').attr('data-placeholder'));

            $(element).parent().find('input').attr('value', '');
            $(element).popover('hide', function() {
                $('.popover').remove();
            });
        });
    });

    $('input[name=\'image\']').change(function (){
        var image_path = $(this).val();

        $.ajax({
            type: 'post',
            url: 'index.php?route=catalog/product/inline&token=<?php echo $token; ?>&product_id=' + $(this).parent().parent().find( "input[name*=\'selected\']").val(),
            data: {image: image_path},
            async: false,
            error: function (xhr, ajaxOptions, thrownError) {
                return false;
            }
        });
    });
    //--></script>
    <script type="text/javascript"><!--
    $(document).ready(function() {
        $.fn.editable.defaults.mode = 'inline';

        $('.product-name').editable({
            url: function (params) {
                $.ajax({
                    type: 'post',
                    url: 'index.php?route=catalog/product/inline&token=<?php echo $token; ?>&product_id=' + $(this).parent().parent().find( "input[name*=\'selected\']").val(),
                    data: {name: params.value},
                    async: false,
                    error: function (xhr, ajaxOptions, thrownError) {
                        return false;
                    }
                })
            },
            showbuttons: false,
        });

        $('.product-price').editable({
            url: function (params) {
                $.ajax({
                    type: 'post',
                    url: 'index.php?route=catalog/product/inline&token=<?php echo $token; ?>&product_id=' + $(this).parent().parent().find( "input[name*=\'selected\']").val(),
                    data: {price: params.value},
                    async: false,
                    error: function (xhr, ajaxOptions, thrownError) {
                        return false;
                    }
                })
            },
            showbuttons: false,
        });

        $('.product-special').editable({
            url: function (params) {
                $.ajax({
                    type: 'post',
                    url: 'index.php?route=catalog/product/inline&token=<?php echo $token; ?>&product_id=' + $(this).parent().parent().parent().find( "input[name*=\'selected\']").val(),
                    data: {special: params.value},
                    async: false,
                    error: function (xhr, ajaxOptions, thrownError) {
                        return false;
                    }
                })
            },
            showbuttons: false,
        });

        $('.product-quantity').editable({
            url: function (params) {
                $.ajax({
                    type: 'post',
                    url: 'index.php?route=catalog/product/inline&token=<?php echo $token; ?>&product_id=' + $(this).parent().parent().parent().find( "input[name*=\'selected\']").val(),
                    data: {quantity: params.value},
                    async: false,
                    error: function (xhr, ajaxOptions, thrownError) {
                        return false;
                    }
                })
            },
            showbuttons: false,
        });

        $('.product-status').editable({
            type: 'select',
            url: function (params) {
                $.ajax({
                    type: 'post',
                    url: 'index.php?route=catalog/product/inline&token=<?php echo $token; ?>&product_id=' + $(this).parent().parent().find( "input[name*=\'selected\']").val(),
                    data: {status: params.value},
                    async: false,
                    error: function (xhr, ajaxOptions, thrownError) {
                        return false;
                    }
                })
            },
            showbuttons: false,
        });
    });

    $('input[name=\'filter_name\']').autocomplete({
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
            $('input[name=\'filter_name\']').val(item['label']);
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
    //--></script></div>
<script type="text/javascript"><!--
function filter() {
    var url = 'index.php?route=catalog/product&token=<?php echo $token; ?>';

    var filter_name = $('input[name=\'filter_name\']').val();

    if (filter_name) {
        url += '&filter_name=' + encodeURIComponent(filter_name);
    }

    var filter_model = $('input[name=\'filter_model\']').val();

    if (filter_model) {
        url += '&filter_model=' + encodeURIComponent(filter_model);
    }

    var filter_category = $('select[name=\'filter_category\']').val();

    if (filter_category != '*') {
        url += '&filter_category=' + encodeURIComponent(filter_category);
    }

    var filter_price = $('input[name=\'filter_price\']').val();

    if (filter_price) {
        url += '&filter_price=' + encodeURIComponent(filter_price);
    }

    var filter_quantity = $('input[name=\'filter_quantity\']').val();

    if (filter_quantity) {
        url += '&filter_quantity=' + encodeURIComponent(filter_quantity);
    }

    var filter_status = $('select[name=\'filter_status\']').val();

    if (filter_status != '*') {
        url += '&filter_status=' + encodeURIComponent(filter_status);
    }

    location = url;
}
//--></script>
<?php echo $footer; ?>
