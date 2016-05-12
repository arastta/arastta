<link href="catalog/view/theme/second/stylesheet/offline.css" rel="stylesheet">
<div class="login">
    <div id="content">
        <div class="container-fluid">
            <div class="row">
                <div class="logo">
                    <img src="<?php echo $thumb; ?>" alt="">
                </div>
                <div class="login-center">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <form action="<?php echo $action; ?>" method="post"  class="login-form form-signin" enctype="multipart/form-data">
                                <h4 class="form-title"><i class="fa fa-lock"></i> <?php echo $text_login; ?></h4>
                                <br />
                                <?php if ($success) { ?>
                                <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
                                <?php } ?>
                                <?php if ($error_warning) { ?>
                                <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
                                <?php } ?>
                                <div class="form-group">
                                    <label class="sr-only" for="input-email"><?php echo $entry_email; ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="sr-only" for="input-password"><?php echo $entry_password; ?></label>
                                    <div class="col-sm-10">
                                        <input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />
                                    </div>
                                </div>
                                <?php if ($languages) { ?>
                                <div class="form-group">
                                    <label for="input-language"><?php echo $entry_language; ?></label>
                                    <div class="input-icon">
                                        <i class="fa fa-flag"></i>
                                        <select name="lang" id="input-language" class="form-control">
                                            <?php foreach ($languages as $language) { ?>
                                            <?php if ($language['code'] == $config_admin_language) { ?>
                                            <option value="<?php echo $language['code']; ?>" selected="selected"><?php echo $language['name']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <?php } ?>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-lg btn-primary btn-block"><i class="fa fa-key"></i> <?php echo $button_login; ?></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
