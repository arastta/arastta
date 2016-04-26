<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" onclick="save('save')" form="form-marketing" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success"><i class="fa fa-check"></i></button>
                <button type="submit" form="form-marketing" data-toggle="tooltip" title="<?php echo $button_saveclose; ?>" class="btn btn-default" data-original-title="Save & Close"><i class="fa fa-save text-success"></i></button>
                <button type="submit" onclick="save('new')" form="form-marketing" data-toggle="tooltip" title="<?php echo $button_savenew; ?>" class="btn btn-default" data-original-title="Save & New"><i class="fa fa-plus text-success"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-marketing" class="form-horizontal">
            <div class="row">
                <div class="left-col col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="general">
                                <div class="form-group required">
                                    <label class="col-sm-12" for="input-name"><?php echo $entry_name; ?></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
                                        <?php if ($error_name) { ?>
                                        <div class="text-danger"><?php echo $error_name; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-description"><?php echo $entry_description; ?></label>
                                    <div class="col-sm-12">
                                        <textarea name="description" rows="5" placeholder="<?php echo $entry_description; ?>" id="input-description" class="form-control"><?php echo $description; ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-12" for="input-code"><span data-toggle="tooltip" title="<?php echo $help_code; ?>"><?php echo $entry_code; ?></span></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="code" value="<?php echo $code; ?>" placeholder="<?php echo $entry_code; ?>" id="input-code" class="form-control" />
                                        <?php if ($error_code) { ?>
                                        <div class="text-danger"><?php echo $error_code; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-example"><span data-toggle="tooltip" title="<?php echo $help_example; ?>"><?php echo $entry_example; ?></span></label>
                                    <div class="col-sm-12">
                                        <input type="text" placeholder="<?php echo $entry_example; ?>" id="input-example1" class="form-control" />
                                        <br />
                                        <input type="text" placeholder="<?php echo $entry_example; ?>" id="input-example2" class="form-control" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script type="text/javascript"><!--
    $('#input-code').on('keyup', function() {
        $('#input-example1').val('<?php echo $store; ?>?tracking=' + $('#input-code').val());
        $('#input-example2').val('<?php echo $store; ?>index.php?route=common/home&tracking=' + $('#input-code').val());
    });

    $('#input-code').trigger('keyup');
    //--></script></div>
<?php echo $footer; ?>
