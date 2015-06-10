<form action="<?php echo $action; ?>" method="post" id="install-form" class="form-horizontal" enctype="multipart/form-data">
    <div class="col-xs-12">
        <div class="form-group">
            <div class="col-xs-offset-4 col-xs-8">
                <h4><?php echo $text_settings_header; ?></h4>
            </div>
        </div>
        <div class="form-group">
            <label for="input-store-name" class="control-label col-xs-4"><?php echo $entry_store_name; ?></label>
            <div class="col-xs-8">
                <input type="text" name="store_name" value="<?php echo $store_name; ?>" placeholder="<?php echo $entry_store_name; ?>" id="input-store-name" class="form-control" />
            </div>
        </div>
        <div class="form-group">
            <label for="input-store-email" class="control-label col-xs-4"><?php echo $entry_store_email; ?></label>
            <div class="col-xs-8">
                <input type="text" name="store_email" value="<?php echo $store_email; ?>" placeholder="<?php echo $entry_store_email; ?>" id="input-store-email" class="form-control" />
            </div>
        </div>
        <div class="form-group">
            <label for="input-admin-email" class="control-label col-xs-4"><?php echo $entry_admin_email; ?></label>
            <div class="col-xs-8">
                <input type="text" name="admin_email" value="<?php echo $admin_email; ?>" placeholder="<?php echo $entry_admin_email; ?>" id="input-admin-email" class="form-control" />
            </div>
        </div>
        <div class="form-group">
            <label for="input-admin-username" class="control-label col-xs-4"><?php echo $entry_admin_username; ?></label>
            <div class="col-xs-8">
                <input type="text" name="admin_username" value="<?php echo $admin_username; ?>" placeholder="<?php echo $entry_admin_username; ?>" id="input-admin-username" class="form-control" />
            </div>
        </div>
        <div class="form-group">
            <label for="input-admin-password" class="control-label col-xs-4"><?php echo $entry_admin_password; ?></label>
            <div class="col-xs-8">
                <input type="password" name="admin_password" value="<?php echo $admin_password; ?>" placeholder="<?php echo $entry_admin_password; ?>" id="input-admin-password" class="form-control" />
            </div>
        </div>

        <div class="form-group pull-left">
            <div class="col-xs-12">
                <button type="button" onclick="displayDatabase();" class="btn btn-default"><i class="fa fa-arrow-left"></i> <?php echo $button_back; ?></button>
            </div>
        </div>

        <div class="form-group pull-right">
            <div class="col-xs-12">
                <button type="button" onclick="saveSettings();" class="btn btn-success"><?php echo $button_next; ?> <i class="fa fa-arrow-right"></i></button>
            </div>
        </div>
    </div>
</form>