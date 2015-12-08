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
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-info-circle"></i> <?php echo $text_list; ?></h3>
            </div>
            <div class="panel-body">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
                    <li><a href="#tab-permissions" data-toggle="tab"><?php echo $tab_permissions; ?></a></li>
                    <li><a href="#tab-php-settings" data-toggle="tab"><?php echo $tab_php_settings; ?></a></li>
                    <li><a href="#tab-php-info" data-toggle="tab"><?php echo $tab_php_info; ?></a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab-general">
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
                    <div class="tab-pane" id="tab-permissions">
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
                    <div class="tab-pane" id="tab-php-settings">
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
                    <div class="tab-pane" id="tab-php-info">
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
<?php echo $footer; ?>
