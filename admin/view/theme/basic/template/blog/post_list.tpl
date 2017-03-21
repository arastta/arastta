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
                                    <button type="button" class="btn btn-default dropdown-toggle basic-filter-button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <div class="filter-type"><?php echo $entry_name; ?></div> <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_name; ?>', 'filter_name');"><?php echo $entry_name; ?></a></li>
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_author; ?>', 'filter_author');"><?php echo $entry_author; ?></a></li>
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_category; ?>', 'filter_category');"><?php echo $entry_category; ?></a></li>
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_status; ?>', 'filter_status');"><?php echo $entry_status; ?></a></li>
                                    </ul>
                                </div>
                                <input type="text" name="filter_name"  value="<?php echo $filter_name; ?>" placeholder="<?php echo $text_filter . $entry_name; ?>" id="input-name" class="form-control filter">
                                <input type="text" name="filter_author"  value="<?php echo $filter_author; ?>" placeholder="<?php echo $text_filter . $entry_author; ?>" id="input-author" class="form-control filter hidden">
                                <select name="filter_category" id="input-category" class="form-control filter hidden">
                                    <option value="*"><?php echo $text_filter . $column_category; ?></option>
                                    <?php foreach ($categories as $category) { ?>
                                    <?php if ($category['category_id'] == $filter_category) { ?>
                                    <option value="<?php echo $category['category_id']; ?>" selected="selected"><?php echo $category['name']; ?></option>
                                    <?php } else { ?>
                                    <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
                                    <?php } ?>
                                    <?php } ?>
                                </select>
                                <select name="filter_status" id="input-status" class="form-control filter hidden">
                                    <option value="*"><?php echo $text_filter . $entry_status; ?></option>
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
                    <?php if (!empty($filter_name) || !empty($filter_author) || !empty($filter_category) || !empty($filter_price) || !empty($filter_quantity) || isset($filter_status)) { ?>
                    <div class="row">
                        <div class="col-lg-12 filter-tag">
                            <?php if ($filter_name) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $entry_name; ?>:</label> <label class="filter-label"> <?php echo $filter_name; ?></label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_name');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                            <?php if ($filter_author) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $entry_author; ?>:</label> <label class="filter-label"> <?php echo $filter_author; ?></label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_author');"><i class="fa fa-times"></i></a>
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
                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-post">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <td class="text-center">
                                    <div id="sort-order-list" data-toggle="tooltip" title="<?php echo $column_sortable; ?>">
                                        <i class="fa fa-sort" aria-hidden="true"></i>
                                    </div>
                                </td>
                                <td style="width: 70px; position: relative;" class="text-center">
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
                                <td class="text-left"><?php if ($sort == 'p2c.category_id') { ?>
                                    <a href="<?php echo $sort_category; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_category; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_category; ?>"><?php echo $column_category; ?></a>
                                    <?php } ?></td>
                                <td class="text-right"><?php echo $column_comments; ?></td>
                                <td class="text-right"><?php echo $column_viewed; ?></td>
                                <td class="text-right"><?php if ($sort == 'b.status') { ?>
                                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                                    <?php } ?></td>
                            </tr>
                            </thead>
                            <?php if ($sortable) { ?>
                            <tbody class="sortable-list">
                            <?php } else { ?>
                            <tbody>
                                <input type="hidden" name="sort_order_type"  id="sort-order-type" value="p.sort_order" class="form-control"/>
                            <?php } ?>
                            <?php if ($posts) { ?>
                            <?php foreach ($posts as $post) { ?>
                            <tr>
                                <td class="text-center sortable">
                                    <?php if ($sortable) { ?>
                                        <i class="fa fa-bars" aria-hidden="true"></i>
                                    <?php } else { ?>
                                    <div data-toggle="tooltip" title="<?php echo $text_sortable; ?>">
                                        <i class="fa fa-bars" aria-hidden="true"></i>
                                    </div>
                                    <?php } ?>
                                </td>
                                <td style="text-align: center;"><?php if (in_array($post['post_id'], $selected)) { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $post['post_id']; ?>" checked="checked" />
                                    <?php } else { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $post['post_id']; ?>" />
                                    <?php } ?></td>
                                <td class="text-center">
                                    <a href="" id="thumb-image-<?php echo $post['post_id']; ?>" data-toggle="thumb-image">
                                        <?php if ($post['image']) { ?>
                                        <img src="<?php echo $post['image']; ?>" alt="<?php echo $post['name']; ?>" class="img-thumbnail bulk-action-image" />
                                        <?php } else { ?>
                                        <span class="img-thumbnail list bulk-action-image"><i class="fa fa-camera fa-2x"></i></span>
                                        <?php } ?>
                                    </a>
                                    <input type="hidden" name="image" value="<?php echo $post['image']; ?>" id="input-image-<?php echo $post['post_id']; ?>" /></td>
                                <td class="text-left"><a href="<?php echo $post['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary btn-sm btn-basic-list"><i class="fa fa-pencil"></i></a>
                                      <span class="post-name" id="name[<?php echo $post['post_id']; ?>]">
                                        <?php echo $post['name']; ?>
                                      </span></td>
                                <td class="text-left"><?php foreach ($categories as $category) { ?>
                                    <?php if (in_array($category['category_id'], $post['category'])) { ?>
                                    <?php echo $category['name'];?><br>
                                    <?php } ?> <?php } ?></td>
                                <td class="text-right"><?php echo $post['comment_total']; ?></td>
                                <td class="text-right"><?php echo $post['viewed']; ?></td>
                                <td class="text-right">
                                    <span class="post-status" data-prepend="<?php echo $text_select; ?>" data-source="{'1': '<?php echo $text_enabled; ?>', '0': '<?php echo $text_disabled; ?>'}"><?php echo $post['status']; ?></span>
                                </td>
                                <td class="hidden">
                                    <input type="hidden" name="items[sort_order][]" value="<?php echo $post['post_id']; ?>" class="form-control"/>
                                </td>
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
            url: 'index.php?route=blog/post/inline&token=<?php echo $token; ?>&post_id=' + $(this).parent().parent().find( "input[name*=\'selected\']").val(),
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

        $('.post-name').editable({
            url: function (params) {
                $.ajax({
                    type: 'post',
                    url: 'index.php?route=blog/post/inline&token=<?php echo $token; ?>&post_id=' + $(this).parent().parent().find( "input[name*=\'selected\']").val(),
                    data: {name: params.value},
                    async: false,
                    error: function (xhr, ajaxOptions, thrownError) {
                        return false;
                    }
                })
            },
            showbuttons: false,
        });

        $('.post-status').editable({
            type: 'select',
            url: function (params) {
                $.ajax({
                    type: 'post',
                    url: 'index.php?route=blog/post/inline&token=<?php echo $token; ?>&post_id=' + $(this).parent().parent().find( "input[name*=\'selected\']").val(),
                    data: {status: params.value},
                    async: false,
                    error: function (xhr, ajaxOptions, thrownError) {
                        return false;
                    }
                })
            },
            showbuttons: false,
        });

        <?php if (!empty($filter_name)) { ?>
        changeFilterType('<?php echo $entry_name; ?>', 'filter_name');
        <?php } elseif (!empty($filter_author)) { ?>
        changeFilterType('<?php echo $entry_author; ?>', 'filter_author');
        <?php } elseif (!empty($filter_category)) { ?>
        changeFilterType('<?php echo $column_category; ?>', 'filter_category');
        <?php } elseif (isset($filter_status)) { ?>
        changeFilterType('<?php echo $column_status; ?>', 'filter_status');
        <?php } ?>
    });
            //--></script>
    <script type="text/javascript"><!--
function filter() {
    var url = 'index.php?route=blog/post&token=<?php echo $token; ?>';

    var filter_name = $('input[name=\'filter_name\']').val();

    if (filter_name) {
        url += '&filter_name=' + encodeURIComponent(filter_name);
    }

    var filter_author = $('input[name=\'filter_author\']').val();

    if (filter_author) {
        url += '&filter_author=' + encodeURIComponent(filter_author);
    }

    var filter_category = $('select[name=\'filter_category\']').val();

    if (filter_category != '*') {
        url += '&filter_category=' + encodeURIComponent(filter_category);
    }

    var filter_status = $('select[name=\'filter_status\']').val();

    if (filter_status != '*') {
        url += '&filter_status=' + encodeURIComponent(filter_status);
    }

    location = url;
}
        //--></script>
    <script type="text/javascript"><!--
        $('input[name=\'filter_name\']').autocomplete({
            'source': function(request, response) {
                $.ajax({
                    url: 'index.php?route=blog/post/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                    dataType: 'json',
                    success: function(json) {
                        response($.map(json, function(item) {
                            return {
                                label: item['name'],
                                value: item['post_id']
                            }
                        }));
                    }
                });
            },
            'select': function(item) {
                $('input[name=\'filter_name\']').val(item['label']);
            }
        });

        $('input[name=\'filter_author\']').autocomplete({
            'source': function(request, response) {
                $.ajax({
                    url: 'index.php?route=user/user/autocomplete&token=<?php echo $token; ?>&filter_user_name=' +  encodeURIComponent(request),
                    dataType: 'json',
                    success: function(json) {
                        response($.map(json, function(item) {
                            return {
                                label: item['firstname'] + ' ' + item['lastname'],
                                value: item['user_id']
                            }
                        }));
                    }
                });
            },
            'select': function(item) {
                $('input[name=\'filter_author\']').val(item['label']);
            }
        });
        //--></script>
</div>
<?php echo $footer; ?>
