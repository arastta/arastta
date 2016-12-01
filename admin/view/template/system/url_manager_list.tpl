<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-success"><i class="fa fa-plus"></i></a>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-url-manager').submit() : false;"><i class="fa fa-trash-o"></i></button>
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
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="input-name"><?php echo $column_seo_url; ?></label>
                                <input type="text" name="filter_seo_url" value="<?php echo $filter_seo_url; ?>" placeholder="<?php echo $column_seo_url; ?>" id="input-name" class="form-control" autocomplete="off" />
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="input-name"><?php echo $column_query; ?></label>
                                <input type="text" name="filter_query" value="<?php echo $filter_query; ?>" placeholder="<?php echo $column_query; ?>" id="input-name" class="form-control" autocomplete="off" />
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label class="control-label"><?php echo $column_type; ?></label>
                                <select name="filter_type" class="form-control">
                                    <option value=""></option>
                                    <?php foreach ($types as $type) { ?>
                                    <?php if ($type['value'] == $filter_type) { ?>
                                    <option value="<?php echo $type['value']; ?>" selected="selected"><?php echo $type['name']; ?></option>
                                    <?php } else { ?>
                                    <option value="<?php echo $type['value']; ?>"><?php echo $type['name']; ?></option>
                                    <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label class="control-label"><?php echo $column_language; ?></label>
                                <select name="filter_language" class="form-control">
                                    <option value=""></option>
                                    <?php foreach ($languages as $language) { ?>
                                    <?php if ($language['language_id'] == $filter_language) { ?>
                                    <option value="<?php echo $language['language_id']; ?>" selected="selected"><?php echo $language['name']; ?></option>
                                    <?php } else { ?>
                                    <option value="<?php echo $language['language_id']; ?>"><?php echo $language['name']; ?></option>
                                    <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                            <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
                        </div>
                    </div>
                </div>
                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-url-manager">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                                <td class="text-left"><?php echo $column_seo_url; ?></td>
                                <td class="text-left" style="width: 1px;"><?php echo $column_query; ?></td>
                                <td class="text-left" style="width: 1px;"><?php echo $column_type; ?></td>
                                <td class="text-left" style="width: 1px;"><?php echo $column_language; ?></td>
                                <td class="text-left" style="width: 1px;"><?php echo $column_action; ?></td>
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
                                <td class="text-left"><?php echo $alias['keyword']; ?></td>
                                <td class="text-left"><?php echo $alias['query']; ?></td>
                                <td class="text-left"><?php echo $alias['type']; ?></td>
                                <td class="text-center"><img src="view/image/flags/<?php echo $alias['language_image']; ?>" title="<?php echo $alias['language_name']; ?>" /></td>
                                <td class="text-right"><a href="<?php echo $alias['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
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
$('#button-filter').on('click', function() {
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
});
//--></script>
<?php echo $footer; ?>
