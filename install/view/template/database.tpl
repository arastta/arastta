<form action="<?php echo $action; ?>" method="post" id="install-form" class="form-horizontal" enctype="multipart/form-data">
    <div class="col-xs-12">
        <div class="form-group">
            <div class="col-xs-offset-4 col-xs-8">
                <h4><?php echo $text_database_header; ?></h4>
            </div>
        </div>
        <div class="form-group">
            <label for="input-database-hostname" class="control-label col-xs-4"><?php echo $entry_db_hostname; ?></label>
            <div class="col-xs-8">
                <input type="text" name="db_hostname" value="<?php echo $db_hostname; ?>" placeholder="<?php echo $help_db_hostname; ?>" id="input-database-hostname" class="form-control" />
            </div>
        </div>
        <div class="form-group">
            <label for="input-database-username" class="control-label col-xs-4"><?php echo $entry_db_username; ?></label>
            <div class="col-xs-8">
                <input type="text" name="db_username" value="<?php echo $db_username; ?>" placeholder="<?php echo $help_db_username; ?>" id="input-database-username" class="form-control" />
            </div>
        </div>
        <div class="form-group">
            <label for="input-database-password" class="control-label col-xs-4"><?php echo $entry_db_password; ?></label>
            <div class="col-xs-8">
                <input type="password" name="db_password" value="<?php echo $db_password; ?>" placeholder="<?php echo $help_db_password; ?>" id="input-database-password" class="form-control" />
            </div>
        </div>
        <div class="form-group">
            <label for="input-database-database" class="control-label col-xs-4"><?php echo $entry_db_database; ?></label>
            <div class="col-xs-8">
                <input type="text" name="db_database" value="<?php echo $db_database; ?>" placeholder="<?php echo $help_db_database; ?>" id="input-database-database" class="form-control" />
            </div>
        </div>
        <div class="form-group">
            <label for="input-database-prefix" class="control-label col-xs-4"><?php echo $entry_db_prefix; ?></label>
            <div class="col-xs-8">
                <input type="text" name="db_prefix" value="<?php echo $db_prefix; ?>" placeholder="<?php echo $help_db_prefix; ?>" id="input-database-prefix" class="form-control" />
            </div>
        </div>
        <div class="collapse" id="advanced-settings">
            <div class="form-group">
                <div class="col-xs-offset-4 col-xs-8">
                    <h4><?php echo $text_advanced_settings; ?></h4>
                </div>
            </div>
            <div class="form-group">
                <label for="input-database-driver" class="control-label col-xs-4"><?php echo $entry_db_driver; ?></label>
                <div class="col-xs-8">
                    <select name="db_driver" id="input-database-driver" class="form-control">
                        <?php if ($db_driver == 'mysqli') { ?>
                        <option value="mysqli" selected="selected">MySQLi</option>
                        <option value="pdo">MySQL (PDO)</option>
                        <?php } else { ?>
                        <option value="mysqli">MySQLi</option>
                        <option value="pdo" selected="selected">MySQL (PDO)</option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        <hr />
        <div class="text-center">
            <button type="button" class="btn-expand-down" data-toggle="collapse" data-target="#advanced-settings" aria-expanded="false" aria-controls="advanced-settings" title="<?php echo $help_advanced_settings; ?>" id="btn-show-advanced"><i class="fa fa-chevron-down"></i></button>
        </div>
        <div class="form-group pull-right">
            <div class="col-xs-12">
                <button type="button" onclick="saveDatabase();" class="btn btn-success"><?php echo $button_next; ?> <i class="fa fa-arrow-right"></i></button>
            </div>
        </div>
    </div>
</form>