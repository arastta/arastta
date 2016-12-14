<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" onclick="save('save')" form="form-url-manager" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success" data-original-title="Save"><i class="fa fa-check"></i></button>
                <button type="submit" form="form-url-manager" data-toggle="tooltip" title="<?php echo $button_saveclose; ?>" class="btn btn-default" data-original-title="Save & Close"><i class="fa fa-save text-success"></i></button>
                <button type="submit" onclick="save('new')" form="form-url-manager" data-toggle="tooltip" title="<?php echo $button_savenew; ?>" class="btn btn-default" data-original-title="Save & New"><i class="fa fa-plus text-success"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-times-circle text-danger"></i></a>
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
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-url-manager" class="form-horizontal">
                    <div class="form-group required">
                        <label class="col-sm-2 control-label"><?php echo $entry_seo_url; ?></label>
                        <div class="col-sm-10">
                            <?php foreach ($languages as $language) { ?>
                            <div class="input-group"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                                <input type="text" name="alias[<?php echo $language['language_id']; ?>][seo_url]" value="<?php echo isset($alias[$language['language_id']]) ? $alias[$language['language_id']]['seo_url'] : ''; ?>" placeholder="<?php echo $entry_seo_url; ?>" class="form-control" />
                                <input type="hidden" name="alias_ids[<?php echo $language['language_id']; ?>]" value="<?php echo isset($alias[$language['language_id']]['url_alias_id']) ? $alias[$language['language_id']]['url_alias_id'] : ''; ?>" placeholder="<?php echo $entry_seo_url; ?>" class="form-control" />
                            </div>
                            <?php if (isset($error_seo_url[$language['language_id']])) { ?>
                            <div class="text-danger"><?php echo $error_seo_url[$language['language_id']]; ?></div>
                            <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-query"><?php echo $entry_query; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="query" value="<?php echo $query; ?>" placeholder="<?php echo $entry_query; ?>" id="input-query" class="form-control" />
                            <?php if ($error_query) { ?>
                            <div class="text-danger"><?php echo $error_query; ?></div>
                            <?php } ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript"><!--
function save(type){
    var input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'button';
    input.value = type;
    form = $("form[id^='form-']").append(input);
    form.submit();
}
//--></script>

<?php echo $footer; ?>
