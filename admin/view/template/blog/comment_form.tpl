<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" onclick="save('save')" form="form-comment" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success" data-original-title="Save"><i class="fa fa-check"></i></button>
                <button type="submit" form="form-comment" data-toggle="tooltip" title="<?php echo $button_saveclose; ?>" class="btn btn-default" data-original-title="Save & Close"><i class="fa fa-save text-success"></i></button>
                <button type="submit" onclick="save('new')" form="form-comment" data-toggle="tooltip" title="<?php echo $button_savenew; ?>" class="btn btn-default" data-original-title="Save & New"><i class="fa fa-plus text-success"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-times-circle text-danger"></i></a></div>
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
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-comment" class="form-horizontal">
                    <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-author"><?php echo $entry_author; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="author" value="<?php echo $author; ?>" placeholder="<?php echo $entry_author; ?>" id="input-author" class="form-control" />
                            <?php if ($error_author) { ?>
                            <div class="text-danger"><?php echo $error_author; ?></div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-post"><span data-toggle="tooltip" title="<?php echo $help_post; ?>"><?php echo $entry_post; ?></span></label>
                        <div class="col-sm-10">
                            <input type="text" name="post" value="<?php echo $post; ?>" placeholder="<?php echo $entry_post; ?>" id="input-post" class="form-control" />
                            <input type="hidden" name="post_id" value="<?php echo $post_id; ?>" />
                            <?php if ($error_post) { ?>
                            <div class="text-danger"><?php echo $error_post; ?></div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-text"><?php echo $entry_text; ?></label>
                        <div class="col-sm-10">
                            <textarea name="text" cols="60" rows="8" placeholder="<?php echo $entry_text; ?>" id="input-text" class="form-control"><?php echo $text; ?></textarea>
                            <?php if ($error_text) { ?>
                            <div class="text-danger"><?php echo $error_text; ?></div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                        <div class="col-sm-10">
                            <label class="radio-inline">
                                <?php if ($status) { ?>
                                <input type="radio" name="status" value="1" checked="checked" />
                                <?php echo $text_yes; ?>
                                <?php } else { ?>
                                <input type="radio" name="status" value="1" />
                                <?php echo $text_yes; ?>
                                <?php } ?>
                            </label>
                            <label class="radio-inline">
                                <?php if (!$status) { ?>
                                <input type="radio" name="status" value="0" checked="checked" />
                                <?php echo $text_no; ?>
                                <?php } else { ?>
                                <input type="radio" name="status" value="0" />
                                <?php echo $text_no; ?>
                                <?php } ?>
                            </label>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript"><!--
    $('input[name=\'post\']').autocomplete({
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
            $('input[name=\'post\']').val(item['label']);
            $('input[name=\'post_id\']').val(item['value']);
        }
    });
    //--></script>
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
