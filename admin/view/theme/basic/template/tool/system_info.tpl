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
        <div class="row">
            <div class="col-sm-6 left-col">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?php echo $tab_general; ?></h3>
                        <div class="pull-right">
                            <div class="panel-chevron"><i class="fa fa-chevron-up rotate-reset"></i></div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="general">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <td class="text-left">
                                            <?php echo $column_setting; ?>
                                        </td>
                                        <td class="text-left">
                                            <?php echo $column_value; ?>
                                        </td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($general as $id => $value) { ?>
                                    <tr>
                                        <td class="text-left"><?php echo ${'text_'.$id}; ?></td>
                                        <td class="text-left"><?php echo $value; ?></td>
                                    </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 right-col">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?php echo $tab_permissions; ?></h3>
                        <div class="pull-right">
                            <div class="panel-chevron permission-chevron"><i class="fa fa-chevron-up rotate-reset"></i></div>
                        </div>
                    </div>
                    <div class="panel-body  permission-body">
                        <div class="permissions">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <td class="text-left">
                                            <?php echo $column_folder; ?>
                                        </td>
                                        <td class="text-left">
                                            <?php echo $column_status; ?>
                                        </td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($permissions as $folder => $status) { ?>
                                    <tr>
                                        <td class="text-left"><?php echo $folder; ?></td>
                                        <td class="text-left"><?php echo ($status == '1') ? '<span class="badge progress-bar-success">'.$text_writable.'</span>' : '<span class="badge progress-bar-danger">'.$text_unwritable.'</span>'; ?></td>
                                    </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?php echo $tab_php_settings; ?></h3>
                        <div class="pull-right">
                            <div class="panel-chevron php-setting-chevron"><i class="fa fa-chevron-up rotate-reset"></i></div>
                        </div>
                    </div>
                    <div class="panel-body php-setting-body">
                        <div class="php-settings">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <td class="text-left">
                                            <?php echo $column_setting; ?>
                                        </td>
                                        <td class="text-left">
                                            <?php echo $column_value; ?>
                                        </td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($php_settings as $id => $value) { ?>
                                    <tr>
                                        <td class="text-left"><?php echo ${'text_'.$id}; ?></td>
                                        <td class="text-left">
                                            <?php
                                             if (is_bool($value)) {
                                               echo ($value == true) ? $text_enabled : $text_disabled;
                                             } else {
                                               echo $value;
                                             }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?php echo $tab_php_info; ?></h3>
                        <div class="pull-right">
                            <div class="panel-chevron php-info-chevron"><i class="fa fa-chevron-up rotate-reset"></i></div>
                        </div>
                    </div>
                    <div class="panel-body php-info-body">
                        <div class="php-settings">
                            <?php if (!empty($php_info)) { ?>
                            <?php echo $php_info; ?></td>
                            <?php } else { ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <tbody>
                                    <tr>
                                        <td class="text-center"><?php echo $text_php_info_disabled; ?></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $footer; ?>
<script type="text/javascript">
$(document).ready(function() {
    $('.permission-body').slideToggle();
    $('.permission-chevron').toggleClass('rotate');

    $('.php-setting-body').slideToggle();
    $('.php-setting-chevron').toggleClass('rotate');

    $('.php-info-body').slideToggle();
    $('.php-info-chevron').toggleClass('rotate');
});
</script>
