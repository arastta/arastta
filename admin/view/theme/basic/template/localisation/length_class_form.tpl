<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" onclick="save('save')" form="form-length-class" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success"><i class="fa fa-check"></i></button>
                <button type="submit" form="form-length-class" data-toggle="tooltip" title="<?php echo $button_saveclose; ?>" class="btn btn-default" data-original-title="Save & Close"><i class="fa fa-save text-success"></i></button>
                <button type="submit" onclick="save('new')" form="form-length-class" data-toggle="tooltip" title="<?php echo $button_savenew; ?>" class="btn btn-default" data-original-title="Save & New"><i class="fa fa-plus text-success"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-length-class" class="form-horizontal">
            <div class="row">
                <div class="col-sm-3 left-col">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="general">
                                <ul class="nav nav-tabs" id="language">
                                    <?php foreach ($languages as $language) { ?>
                                    <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                                    <?php } ?>
                                </ul>
                                <div class="tab-content">
                                    <?php foreach ($languages as $language) { ?>
                                    <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
                                        <div class="form-group required">
                                            <label class="col-sm-12" for="input-title<?php echo $language['language_id']; ?>"><?php echo $entry_title; ?></label>
                                            <div class="col-sm-12">
                                                <input type="text" name="length_class_description[<?php echo $language['language_id']; ?>][title]" id="input-title<?php echo $language['language_id']; ?>" value="<?php echo isset($length_class_description[$language['language_id']]) ? $length_class_description[$language['language_id']]['title'] : ''; ?>" placeholder="<?php echo $entry_title; ?>" class="form-control" />
                                                <?php if (isset($error_title[$language['language_id']])) { ?>
                                                <div class="text-danger"><?php echo $error_title[$language['language_id']]; ?></div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="form-group required">
                                            <label class="col-sm-12" for="input-unit<?php echo $language['language_id']; ?>"><?php echo $entry_unit; ?></label>
                                            <div class="col-sm-12">
                                                <input type="text" name="length_class_description[<?php echo $language['language_id']; ?>][unit]" id="input-unit<?php echo $language['language_id']; ?>" value="<?php echo isset($length_class_description[$language['language_id']]) ? $length_class_description[$language['language_id']]['unit'] : ''; ?>" placeholder="<?php echo $entry_unit; ?>" class="form-control" />
                                                <?php if (isset($error_unit[$language['language_id']])) { ?>
                                                <div class="text-danger"><?php echo $error_unit[$language['language_id']]; ?></div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-9 right-col">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><?php echo $text_items; ?></h3>
                            <div class="pull-right">
                                <div class="panel-chevron"><i class="fa fa-chevron-up rotate-reset"></i></div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="items">
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-value"><span data-toggle="tooltip" title="<?php echo $help_value; ?>"><?php echo $entry_value; ?></span></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="value" value="<?php echo $value; ?>" placeholder="<?php echo $entry_value; ?>" id="input-value" class="form-control" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript"><!--
$('#language a:first').tab('show');
//--></script>
<?php echo $footer; ?>
