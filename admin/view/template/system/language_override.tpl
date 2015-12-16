<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-language-override" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success"><i class="fa fa-check"></i></button>
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
                                <label class="control-label" for="input-name"><?php echo $column_text; ?></label>
                                <input type="text" name="filter_text" value="<?php echo $filter_text; ?>" placeholder="<?php echo $column_text; ?>" id="input-name" class="form-control" autocomplete="off" />
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="input-name"><?php echo $column_path; ?></label>
                                <input type="text" name="filter_path" value="<?php echo $filter_path; ?>" placeholder="<?php echo $column_path; ?>" id="input-name" class="form-control" autocomplete="off" />
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label class="control-label"><?php echo $column_language; ?></label>
                                <select name="filter_language" class="form-control">
                                    <option value="*"></option>
                                    <?php foreach ($languages as $language) { ?>
                                    <?php if ($language == $filter_language) { ?>
                                    <option value="<?php echo $language; ?>" selected="selected"><?php echo ucfirst($language); ?></option>
                                    <?php } else { ?>
                                    <option value="<?php echo $language; ?>"><?php echo $language; ?></option>
                                    <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label class="control-label"><?php echo $column_client; ?></label>
                                <select name="filter_client" class="form-control">
                                    <?php if ($filter_client == 'admin') { ?>
                                    <option value="admin" selected="selected"><?php echo $text_admin; ?></option>
                                    <option value="catalog"><?php echo $text_catalog; ?></option>
                                    <?php } else { ?>
                                    <option value="admin"><?php echo $text_admin; ?></option>
                                    <option value="catalog" selected="selected"><?php echo $text_catalog; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
                        </div>
                    </div>
                </div>
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-language-override">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <td class="text-left"><?php echo $column_text; ?></td>
                                <td class="text-left" style="width: 1px;"><?php echo $column_constant; ?></td>
                                <td class="text-left" style="width: 1px;"><?php echo $column_path; ?></td>
                                <td class="text-center" style="width: 1px;"><?php echo $column_language; ?></td>
                                <td class="text-left" style="width: 1px;"><?php echo $column_client; ?></td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ($files) { ?>
                            <?php foreach ($files as $key => $strings) { ?>
                            <?php foreach ($strings as $var => $value) {
                                                             $temp = explode('_', $key);

                                                             if (empty($temp[0]) or empty($temp[1])) {
                                                             continue;
                                                             }

                                                             $language = $temp[0];
                                                             $path = $temp[1];

                                                             if (!empty($temp[2])) {
                                                             $path .= '/'.$temp[2];
                                                             }

                                                             if (!empty($temp[3])) {
                                                             $path .= '_'.$temp[3];
                                                             }

                                                             $path .= '.php';
                                                             ?>
                            <tr>
                                <td class="text-left"><input type="text" name="lstrings[<?php echo $key; ?>][<?php echo $var; ?>]" value="<?php echo $value; ?>" class="form-control input-full-width" /></td>
                                <td class="text-left"><?php echo $var; ?></td>
                                <td class="text-left"><?php echo $path; ?></td>
                                <td class="text-center"><?php echo $language; ?></td>
                                <td class="text-left"><?php echo ucfirst($filter_client); ?></td>
                            </tr>
                            <?php } ?>
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
    url = 'index.php?route=system/language_override&token=<?php echo $token; ?>';

    var filter_text = $('input[name=\'filter_text\']').val();

    if (filter_text) {
        url += '&filter_text=' + encodeURIComponent(filter_text);
    }

    var filter_client = $('select[name=\'filter_client\']').val();

    if (filter_client) {
        url += '&filter_client=' + encodeURIComponent(filter_client);
    }

    var filter_language = $('select[name=\'filter_language\']').val();

    if (filter_language != '*') {
        url += '&filter_language=' + encodeURIComponent(filter_language);
    }

    var filter_folder = $('input[name=\'filter_folder\']').val();

    if (filter_folder) {
        url += '&filter_folder=' + encodeURIComponent(filter_folder);
    }

    var filter_path = $('input[name=\'filter_path\']').val();

    if (filter_path) {
        url += '&filter_path=' + encodeURIComponent(filter_path);
    }

    location = url;
});
//--></script>
<?php echo $footer; ?>
