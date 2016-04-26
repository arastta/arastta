<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right"><a href="<?php echo $download; ?>" data-toggle="tooltip" title="<?php echo $button_download; ?>" class="btn btn-primary"><i class="fa fa-download"></i></a><a onclick="confirmItemSetLink('<?php echo $text_confirm_title; ?>', '<?php echo $text_confirm; ?>', '<?php echo $clear; ?>');" data-toggle="tooltip" title="<?php echo $button_clear; ?>" class="btn btn-danger"><i class="fa fa-eraser"></i></a></div>
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
            <div class="panel-body">
                <textarea wrap="off" rows="15" readonly="readonly" class="form-control input-full-width"><?php echo $log; ?></textarea>
            </div>
        </div>
    </div>
</div>
<?php echo $footer; ?>
