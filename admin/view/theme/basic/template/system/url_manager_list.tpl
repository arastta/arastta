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
                                        <div class="filter-type"><?php echo $column_seo_url; ?></div> <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $column_seo_url; ?>', 'filter_seo_url');"><?php echo $column_seo_url; ?></a></li>
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $column_query; ?>', 'filter_query');"><?php echo $column_query; ?></a></li>
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $column_type; ?>', 'filter_type');"><?php echo $column_type; ?></a></li>
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $column_language; ?>', 'filter_language');"><?php echo $column_language; ?></a></li>
                                    </ul>
                                </div>
                                <input type="text" name="filter_seo_url"  value="<?php echo $filter_seo_url; ?>" placeholder="<?php echo $text_filter . $column_seo_url; ?>" id="input-name" class="form-control filter">
                                <input type="text" name="filter_query" value="<?php echo $filter_query; ?>" placeholder="<?php echo $text_filter . $column_query; ?>" id="input-query" class="form-control filter hidden" />
                                <select name="filter_type" class="form-control filter hidden">
                                    <option value=""><?php echo $text_filter . $column_type; ?></option>
                                    <?php foreach ($types as $type) { ?>
                                    <?php if ($type['value'] == $filter_type) { ?>
                                    <option value="<?php echo $type['value']; ?>" selected="selected"><?php echo $type['name']; ?></option>
                                    <?php } else { ?>
                                    <option value="<?php echo $type['value']; ?>"><?php echo $type['name']; ?></option>
                                    <?php } ?>
                                    <?php } ?>
                                </select>
                                <select name="filter_language" class="form-control filter hidden">
                                    <option value=""><?php echo $text_filter . $column_language; ?></option>
                                    <?php foreach ($languages as $language) { ?>
                                    <?php if ($language['language_id'] == $filter_language) { ?>
                                    <option value="<?php echo $language['language_id']; ?>" selected="selected"><?php echo $language['name']; ?></option>
                                    <?php } else { ?>
                                    <option value="<?php echo $language['language_id']; ?>"><?php echo $language['name']; ?></option>
                                    <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <?php if (!empty($filter_seo_url) || !empty($filter_query) || isset($filter_type) || !empty($filter_language)) { ?>
                    <div class="row">
                        <div class="col-lg-12 filter-tag">
                            <?php if ($filter_seo_url) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $column_seo_url; ?>:</label> <label class="filter-label"> <?php echo $filter_seo_url; ?></label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_seo_url');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                            <?php if ($filter_query) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $column_query; ?>:</label> <label class="filter-label"> <?php echo $filter_query; ?></label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_query');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                            <?php if ($filter_type) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $column_type; ?>:</label> <label class="filter-label"> <?php echo $filter_type; ?></label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_type');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                            <?php if ($filter_language) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $column_language; ?>:</label> <label class="filter-label"> <?php echo $filter_language; ?></label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_language');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-url-manager">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
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
                                              <li><a onclick="confirmItem('<?php echo $text_confirm_title; ?>', '<?php echo $text_confirm; ?>');"><i class="fa fa-trash-o"></i> <?php echo $button_delete; ?></a></li>
                                          </ul>
                                        </span>
                                    </div></td>
                                <td class="text-left"><?php echo $column_seo_url; ?></td>
                                <td class="text-left" style="width: 1px;"><?php echo $column_query; ?></td>
                                <td class="text-left" style="width: 1px;"><?php echo $column_type; ?></td>
                                <td class="text-left" style="width: 1px;"><?php echo $column_language; ?></td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ($aliases) { ?>
                            <?php foreach ($aliases as $alias) { ?>
                            <tr>
                                <td class="text-center"><?php if (in_array($alias['url_alias_id'], $selected)) { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $alias['url_alias_id']; ?>" checked="checked" />
                                    <?php } else { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $alias['url_alias_id']; ?>" />
                                    <?php } ?>
                                </td>
                                <td class="text-left">
                                    <a href="<?php echo $alias['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary btn-sm btn-basic-list"><i class="fa fa-pencil"></i></a>
                                    <span class="alias-keyword" id="name[<?php echo $alias['url_alias_id']; ?>]">
                                        <?php echo $alias['keyword']; ?>
                                      </span></td>
                                <td class="text-left"><?php echo $alias['query']; ?></td>
                                <td class="text-left"><?php echo $alias['type']; ?></td>
                                <td class="text-center"><img src="view/image/flags/<?php echo $alias['language_image']; ?>" title="<?php echo $alias['language_name']; ?>" /></td>
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
$(document).ready(function() {
    $.fn.editable.defaults.mode = 'inline';

    $('.alias-keyword').editable({
        url: function (params) {
            $.ajax({
                type: 'post',
                url: 'index.php?route=system/url_manager/inline&token=<?php echo $token; ?>&url_alias_id=' + $(this).parent().parent().find( "input[name*=\'selected\']").val(),
                data: {keyword: params.value},
                async: false,
                error: function (xhr, ajaxOptions, thrownError) {
                    return false;
                }
            })
        },
        showbuttons: false,
    });
});

$(document).ready(function() {
    <?php if (!empty($filter_seo_url)) { ?>
    changeFilterType('<?php echo $column_seo_url; ?>', 'filter_seo_url');
    <?php } elseif (!empty($filter_query)) { ?>
    changeFilterType('<?php echo $column_query; ?>', 'filter_query');
    <?php } elseif (!empty($filter_type)) { ?>
    changeFilterType('<?php echo $column_type; ?>', 'filter_type');
    <?php } elseif (!empty($filter_language)) { ?>
    changeFilterType('<?php echo $column_language; ?>', 'filter_language');
    <?php } ?>
});

function filter() {
    url = 'index.php?route=system/url_manager&token=<?php echo $token; ?>';

    var filter_seo_url = $('input[name=\'filter_seo_url\']').val();

    if (filter_seo_url) {
        url += '&filter_seo_url=' + encodeURIComponent(filter_seo_url);
    }

    var filter_query = $('input[name=\'filter_query\']').val();

    if (filter_query) {
        url += '&filter_query=' + encodeURIComponent(filter_query);
    }

    var filter_type = $('select[name=\'filter_type\']').val();

    if (filter_type) {
        url += '&filter_type=' + encodeURIComponent(filter_type);
    }

    var filter_language = $('select[name=\'filter_language\']').val();

    if (filter_language) {
        url += '&filter_language=' + encodeURIComponent(filter_language);
    }

    location = url;
}
//--></script>
<?php echo $footer; ?>
