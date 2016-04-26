<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" onclick="save('save')" form="form-google-sitemap" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success"><i class="fa fa-check"></i></button>
                <button type="submit" form="form-google-sitemap" data-toggle="tooltip" title="<?php echo $button_saveclose; ?>" class="btn btn-default" data-original-title="Save & Close"><i class="fa fa-save text-success"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-google-sitemap" class="form-horizontal">
            <div class="row">
                <div class="left-col col-sm-8">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="general">
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-data-feed"><?php echo $entry_data_feed; ?></label>
                                    <div class="col-sm-12">
                                        <textarea rows="5" readonly="readonly" id="input-data-feed" class="form-control"><?php echo $data_feed; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="right-col col-sm-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><?php echo $text_publish; ?></h3>
                            <div class="pull-right">
                                <div class="panel-chevron"><i class="fa fa-chevron-up rotate-reset"></i></div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="publish">
                                <div class="form-group">
                                    <label class="col-sm-12"><?php echo $text_enabled; ?></label>
                                    <div class="col-sm-12">
                                        <label class="radio-inline">
                                            <?php if ($google_sitemap_status) { ?>
                                            <input type="radio" name="google_sitemap_status" value="1" checked="checked" />
                                            <?php echo $text_enabled; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="google_sitemap_status" value="1" />
                                            <?php echo $text_enabled; ?>
                                            <?php } ?>
                                        </label>
                                        <label class="radio-inline">
                                            <?php if (!$google_sitemap_status) { ?>
                                            <input type="radio" name="google_sitemap_status" value="0" checked="checked" />
                                            <?php echo $text_disabled; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="google_sitemap_status" value="0" />
                                            <?php echo $text_disabled; ?>
                                            <?php } ?>
                                        </label>
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
<?php echo $footer; ?>
