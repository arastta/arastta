<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-success"><i class="fa fa-plus"></i></a>
                <a href="<?php echo $upload; ?>" data-toggle="tooltip" title="<?php echo $text_upload; ?>" class="btn btn-default"><i class="fa fa-upload"></i></a>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_enable; ?>" class="btn btn-default" onclick="changeStatus(1);"><i class="fa fa-check-circle text-success"></i></button>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_disable; ?>" class="btn btn-default" onclick="changeStatus(0);"><i class="fa fa-times-circle text-danger"></i></button>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-language').submit() : false;"><i class="fa fa-trash-o"></i></button>
            </div>
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
                <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-language">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <td class="text-center" style="width: 1px;">
                                    <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" />
                                </td>
                                <td class="text-left" style="width: 35%;">
                                    <?php if ($sort == 'name') { ?>
                                    <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $entry_name; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_name; ?>"><?php echo $entry_name; ?></a>
                                    <?php } ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($sort == 'code') { ?>
                                    <a href="<?php echo $sort_code; ?>" class="<?php echo strtolower($order); ?>"><?php echo $entry_code; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_code; ?>"><?php echo $entry_code; ?></a>
                                    <?php } ?>
                                </td>
                                <td class="text-center">
                                    <?php echo $entry_image; ?>
                                </td>
                                <td class="text-center">
                                    <?php echo $entry_directory; ?>
                                </td>
                                <td class="text-center">
                                    <?php echo $entry_status; ?>
                                </td>
                                <td class="text-right">
                                    <?php if ($sort == 'sort_order') { ?>
                                    <a href="<?php echo $sort_sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $entry_sort_order; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_sort_order; ?>"><?php echo $entry_sort_order; ?></a>
                                    <?php } ?>
                                </td>
                                <td class="text-right">
                                    <?php echo $column_action; ?>
                                </td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ($languages) { ?>
                            <?php foreach ($languages as $language) { ?>
                            <tr>
                                <td class="text-center"><?php if (in_array($language['language_id'], $selected)) { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $language['language_id']; ?>" checked="checked" />
                                    <?php } else { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $language['language_id']; ?>" />
                                    <?php } ?>
                                </td>
                                <td class="text-left">
                                    <?php echo $language['name']; ?>
                                </td>
                                <td class="text-center">
                                    <?php echo $language['code']; ?>
                                </td>
                                <td class="text-center">
                                    <img src="view/image/flags/<?php echo $language['image']; ?>" alt="<?php echo $language['name']; ?>" title="<?php echo $language['name']; ?>">
                                </td>
                                <td class="text-center">
                                    <?php echo $language['directory']; ?>
                                </td>
                                <td class="text-center">
                                    <?php echo ($language['status'] == 1) ? $text_enabled : $text_disabled; ?>
                                </td>
                                <td class="text-right">
                                    <?php echo $language['sort_order']; ?>
                                </td>
                                <td class="text-right">
                                    <a href="<?php echo $language['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                                </td>
                            </tr>
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
<?php echo $footer; ?>
