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
                                        <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $column_description; ?>', 'filter_description');"><?php echo $column_description; ?></a></li>
                                    </ul>
                                </div>
                                <input type="text" name="filter_name"  value="<?php echo $filter_name; ?>" id="input-name" class="form-control filter">
                                <input type="text" name="filter_description"  value="<?php echo $filter_description; ?>" id="input-description" class="form-control filter hidden">
                            </div>
                        </div>
                    </div>
                    <?php if (!empty($filter_name) || !empty($filter_description)) { ?>
                    <div class="row">
                        <div class="col-lg-12 filter-tag">
                            <?php if ($filter_name) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $column_name; ?>:</label> <label class="filter-label"> <?php echo $filter_name; ?></label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_name');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                            <?php if ($filter_description) { ?>
                            <div class="filter-info pull-left">
                                <label class="control-label"><?php echo $column_description; ?>:</label> <label class="filter-label"> <?php echo $filter_description; ?></label>
                                <a class="filter-remove" onclick="removeFilter(this, 'filter_description');"><i class="fa fa-times"></i></a>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>                    
                </div>
                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-geo-zone">
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
                                <td class="text-left"><?php if ($sort == 'name') { ?>
                                    <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                                    <?php } ?></td>
                                <td class="text-left"><?php if ($sort == 'description') { ?>
                                    <a href="<?php echo $sort_description; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_description; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_description; ?>"><?php echo $column_description; ?></a>
                                    <?php } ?></td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ($geo_zones) { ?>
                            <?php foreach ($geo_zones as $geo_zone) { ?>
                            <tr>
                                <td class="text-center"><?php if (in_array($geo_zone['geo_zone_id'], $selected)) { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $geo_zone['geo_zone_id']; ?>" checked="checked" />
                                    <?php } else { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $geo_zone['geo_zone_id']; ?>" />
                                    <?php } ?></td>
                                <td class="text-left">
                                    <a href="<?php echo $geo_zone['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary btn-sm btn-basic-list"><i class="fa fa-pencil"></i></a>
                                    <?php echo $geo_zone['name']; ?></td>
                                <td class="text-left"><?php echo $geo_zone['description']; ?></td>
                            </tr>
                            <?php } ?>
                            <?php } else { ?>
                            <tr>
                                <td class="text-center" colspan="3"><?php echo $text_no_results; ?></td>
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
$('input[name=\'filter_name\']').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=localisation/geo_zone/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['name'],
                        value: item['geo_zone_id']
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

$('input[name=\'filter_description\']').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=localisation/geo_zone/autocomplete&token=<?php echo $token; ?>&filter_description=' +  encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['description'],
                        value: item['geo_zone_id']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('input[name=\'filter_description\']').val(item['label']);
        filter();
    }
});
//--></script>
<script type="text/javascript"><!--
function filter() {
    var url = 'index.php?route=localisation/geo_zone&token=<?php echo $token; ?>';

    var filter_name = $('input[name=\'filter_name\']').val();

    if (filter_name) {
        url += '&filter_name=' + encodeURIComponent(filter_name);
    }

    var filter_description = $('input[name=\'filter_description\']').val();

    if (filter_description) {
        url += '&filter_description=' + encodeURIComponent(filter_description);
    }

    location = url;
}
//--></script>
<?php echo $footer; ?>
