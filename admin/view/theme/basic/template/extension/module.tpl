<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
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
        <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_layout; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-puzzle-piece"></i> <?php echo $text_list; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-module" class="form-horizontal">
                    <div class="table-responsive">
                        <table class="table table-hover" style="width:97%; margin-left: 20px;">
                            <thead>
                            <tr>
                                <td class="text-left"><?php echo $column_name; ?></td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ($extensions) { ?>
                            <?php foreach ($extensions as $extension) { ?>
                            <tr>
                                <td>
                                    <?php if (!$extension['installed']) { ?>
                                    <a href="<?php echo $extension['install']; ?>" data-toggle="tooltip" title="<?php echo $button_install; ?>"><i class="fa fa-plus-circle"></i></a>
                                    <?php } else { ?>
                                    <a onclick="confirmItemSetLink('<?php echo $text_confirm_title; ?>', '<?php echo $text_confirm; ?>', '<?php echo $extension['uninstall']; ?>');" data-toggle="tooltip" title="<?php echo $button_uninstall; ?>"><i class="fa fa-minus-circle"></i></a>
                                    <?php } ?>
                                    <?php if ($extension['installed']) { ?>
                                    <a href="<?php echo $extension['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>"><i class="fa fa-pencil"></i></a>
                                    <?php } ?>
                                    <?php echo $extension['name']; ?></td>
                            </tr>
                            <?php foreach ($extension['module'] as $module) { ?>
                            <tr>
                                <td class="text-left">
                                    <a onclick="confirmItemSetLink('<?php echo $text_confirm_title; ?>', '<?php echo $text_confirm; ?>', '<?php echo $module['delete']; ?>');" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger btn-sm btn-basic-list"><i class="fa fa-trash-o"></i></a> 
                                    <a href="<?php echo $module['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary btn-sm btn-basic-list"><i class="fa fa-pencil"></i></a>
                                    <?php echo $module['name']; ?></td>
                            </tr>
                            <?php } ?>
                            <?php } ?>
                            <?php } else { ?>
                            <tr>
                                <td class="text-center" colspan="2"><?php echo $text_no_results; ?></td>
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php echo $footer; ?>
