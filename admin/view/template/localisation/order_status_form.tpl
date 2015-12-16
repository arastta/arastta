<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" onclick="save('save')" form="form-order-status" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success"><i class="fa fa-check"></i></button>
                <button type="submit" form="form-order-status" data-toggle="tooltip" title="<?php echo $button_saveclose; ?>" class="btn btn-default" data-original-title="Save & Close"><i class="fa fa-save text-success"></i></button>
                <button type="submit" onclick="save('new')" form="form-order-status" data-toggle="tooltip" title="<?php echo $button_savenew; ?>" class="btn btn-default" data-original-title="Save & New"><i class="fa fa-plus text-success"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-times-circle text-danger"></i></a></div>
            <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
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
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-order-status" class="form-horizontal">
                    <div class="tab-pane active in" id="tab-general">
                        <ul class="nav nav-tabs" id="language">
                            <?php foreach ($languages as $language) { ?>
                            <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                            <?php } ?>
                        </ul>
                        <div class="tab-content">
                            <?php foreach ($languages as $language) { ?>
                            <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
                                <div class="form-group required">
                                    <label class="col-sm-2 control-label"><?php echo $entry_name; ?></label>
                                    <div class="col-sm-10">
                                        <div class="input-group"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                                            <input type="text" name="order_status[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($order_status[$language['language_id']]) ? $order_status[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" class="form-control" />
                                        </div>
                                        <?php if (isset($error_name[$language['language_id']])) { ?>
                                        <div class="text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><?php echo $entry_message; ?></label>
                                    <div class="col-sm-10">
                                        <div class="input-group" style="max-width: 100%;"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                                            <textarea name="order_status[<?php echo $language['language_id']; ?>][message]" placeholder="<?php echo $entry_message; ?>" id="input-message-<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($order_status[$language['language_id']]) ? $order_status[$language['language_id']]['message'] : ''; ?></textarea>
                                        </div>
                                        <?php if (isset($error_message[$language['language_id']])) { ?>
                                        <div class="text-danger"><?php echo $error_message[$language['language_id']]; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_email_template; ?>"><?php echo $entry_email_template; ?></span></label>
                                    <div class="col-sm-10">
                                        <div class="input-group" style="max-width: 100%;"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                                            <textarea name="order_status[<?php echo $language['language_id']; ?>][email_template]" placeholder="<?php echo $entry_email_template; ?>" id="input-email-template-<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($email_template[$language['language_id']]) ? $email_template[$language['language_id']]['description'] : ''; ?></textarea>
                                        </div>
                                        <?php if (isset($error_message[$language['language_id']])) { ?>
                                        <div class="text-danger"><?php echo $error_message[$language['language_id']]; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript"><!--
$(document).ready(function() {
    <?php foreach ($languages as $language) { ?>
        textEditor('#input-message-<?php echo $language['language_id']; ?>, #input-email-template-<?php echo $language['language_id']; ?>');
        <?php } ?>
    });
//--></script>
<script type="text/javascript"><!--
$('#language a:first').tab('show');
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
