<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <h1><?php echo $heading_title; ?></h1>
            <div class="pull-right">
                <a href="<?php echo $check; ?>" data-toggle="tooltip" title="<?php echo $button_check; ?>" class="btn btn-warning"><i class="fa fa-history"></i></a>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <?php if (isset($text_error)) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> &nbsp;<?php echo $text_error; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <?php if (isset($text_success)) { ?>
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> &nbsp;<?php echo $text_success; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <?php if (!empty($updates)) { ?>
        <div class="alert alert-info"><i class="fa fa-info-circle"></i> &nbsp;<?php echo $text_maintenance; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } else { ?>
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> &nbsp;<?php echo $text_up_to_date; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-shopping-cart"></i>Arastta</h3>
            </div>
            <div class="panel-body">
                <?php if (!empty($updates['core'])) { ?>
                <h4><?php echo $text_update_core; ?></h4> <a href="<?php echo $update.'&product_id=core&version='.$updates['core']; ?>" data-toggle="tooltip" title="<?php echo sprintf($button_core, $updates['core']); ?>" class="btn btn-warning"><i class="fa fa-refresh"></i> &nbsp;<?php echo sprintf($button_core, $updates['core']); ?></a> <a href="<?php echo $changelog.'&version='.$updates['core']; ?>" data-toggle="tooltip" title="<?php echo $button_changelog; ?>" class="btn btn-default popup"><i class="fa fa-exchange"></i> &nbsp;<?php echo $button_changelog; ?></a>
                <?php } else { ?>
                <h4><?php echo $text_latest_core; ?></h4>
                <?php } ?>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-cube"></i><?php echo $text_extension; ?></h3>
            </div>
            <div class="panel-body">
                <?php if (!empty($updates['extension'])) { ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <td class="text-left"><?php echo $column_name; ?></td>
                            <td class="text-center" style="width: 150px;"><?php echo $column_installed; ?></td>
                            <td class="text-center" style="width: 150px;"><?php echo $column_latest; ?></td>
                            <td class="text-center" style="width: 100px;"><?php echo $column_action; ?></td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($updates['extension'] as $product_id => $latest_version) { ?>
                        <tr>
                            <td class="text-left"><?php echo $addons[$product_id]['product_name']; ?></td>
                            <td class="text-center"><?php echo $addons[$product_id]['product_version']; ?></td>
                            <td class="text-center"><?php echo $latest_version; ?></td>
                            <td class="text-center"><a href="<?php echo $update.'&product_id='.$product_id.'&version='.$latest_version; ?>" data-toggle="tooltip" title="<?php echo $button_extension; ?>" class="btn btn-primary"><i class="fa fa-refresh"></i></a></td>
                        </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                <?php } else { ?>
                <?php echo $text_latest_extension; ?>
                <?php } ?>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-paint-brush"></i><?php echo $text_theme; ?></h3>
            </div>
            <div class="panel-body">
                <?php if (!empty($updates['theme'])) { ?>
                <div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> &nbsp;<?php echo $text_update_theme_warning; ?>
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <td class="text-left"><?php echo $column_name; ?></td>
                            <td class="text-center" style="width: 150px;"><?php echo $column_installed; ?></td>
                            <td class="text-center" style="width: 150px;"><?php echo $column_latest; ?></td>
                            <td class="text-center" style="width: 100px;"><?php echo $column_action; ?></td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($updates['theme'] as $product_id => $latest_version) { ?>
                        <tr>
                            <td class="text-left"><?php echo $addons[$product_id]['product_name']; ?></td>
                            <td class="text-center"><?php echo $addons[$product_id]['product_version']; ?></td>
                            <td class="text-center"><?php echo $latest_version; ?></td>
                            <td class="text-center"><a href="<?php echo $update.'&product_id='.$product_id.'&version='.$latest_version; ?>" data-toggle="tooltip" title="<?php echo $button_theme; ?>" class="btn btn-primary"><i class="fa fa-refresh"></i></a></td>
                        </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                <?php } else { ?>
                <?php echo $text_latest_theme; ?>
                <?php } ?>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-flag"></i><?php echo $text_translation; ?></h3>
            </div>
            <div class="panel-body">
                <?php if (!empty($updates['translation'])) { ?>
                <div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> &nbsp;<?php echo $text_update_translation_warning; ?>
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <td class="text-left"><?php echo $column_name; ?></td>
                            <td class="text-center" style="width: 150px;"><?php echo $column_installed; ?></td>
                            <td class="text-center" style="width: 150px;"><?php echo $column_latest; ?></td>
                            <td class="text-center" style="width: 100px;"><?php echo $column_action; ?></td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($updates['translation'] as $product_id => $latest_version) { ?>
                        <tr>
                            <td class="text-left"><?php echo $addons[$product_id]['product_name']; ?></td>
                            <td class="text-center"><?php echo $addons[$product_id]['product_version']; ?></td>
                            <td class="text-center"><?php echo $latest_version; ?></td>
                            <td class="text-center"><a href="<?php echo $update.'&product_id='.$product_id.'&version='.$latest_version; ?>" data-toggle="tooltip" title="<?php echo $button_translation; ?>" class="btn btn-primary"><i class="fa fa-refresh"></i></a></td>
                        </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                <?php } else { ?>
                <?php echo $text_latest_translation; ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php echo $footer; ?>
