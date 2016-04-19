<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <a href="<?php echo $extension_installer; ?>" data-toggle="tooltip" title="<?php echo $button_installer; ?>" class="btn btn-default" data-original-title="<?php echo $button_installer; ?>"><i class="fa fa-upload"></i></a>
                <a href="<?php echo $extension_modifications; ?>" data-toggle="tooltip" title="<?php echo $button_modifications; ?>" class="btn btn-default" data-original-title="<?php echo $button_modifications; ?>"><i class="fa fa-random"></i></a>
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
        <?php if ($success) { ?>
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $heading_title; ?></h3>
            </div>
            <div class="panel-body">
                <?php if ($data['changeApiKey']) { ?>
                <form action="<?php echo $action; ?>" method="post" class="form-horizontal" enctype="multipart/form-data" id="form-api_key">
                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-api_key"><span data-toggle="tooltip" title="<?php echo $help_api_key; ?>"><a href="<?php echo $api_key_href; ?>" target="_blank"><?php echo $entry_api_key; ?></a></span></label>
                            <div class="col-sm-10">
                                <input type="password" name="api_key" placeholder="<?php echo $entry_api_key; ?>" id="input-api_key" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"></label>
                            <div class="col-sm-10">
                                <input type="submit" class="btn btn-primary" value="<?php echo $button_continue; ?>" />
                            </div>
                        </div>
                    </fieldset>
                </form>
                <?php } elseif (empty($data['api_key']) || isset($data['error'])) { ?>
                    <div id="content">
                        <div class="col-sm-6">
                            <div class="jumbotron">
                                <h2><?php echo $text_login_api_key; ?></h2>
                                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-api_key">
                                    <div class="form-group">
                                        <label class="control-label" for="input-api_key"><span data-toggle="tooltip" title="<?php echo $help_api_key; ?>"><a href="<?php echo $api_key_href; ?>" target="_blank"><?php echo $entry_api_key; ?></a></span></label>
                                        <input type="password" name="api_key" placeholder="<?php echo $entry_api_key; ?>" id="input-api_key" class="form-control" />
                                    </div>
                                    <input type="submit" class="btn btn-primary" value="<?php echo $button_continue; ?>" />
                                </form>
                            </div>
                            <div class="jumbotron">
                                <h2><?php echo $text_register; ?></h2>
                                <p><?php echo $text_by_registering_account; ?></p>
                                <a onclick="<?php echo $register; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="jumbotron">
                                <h2><?php echo $text_login_email; ?></h2>
                                <p><?php echo $text_signin_same_email; ?></p>
                                <form>
                                    <div class="form-group">
                                        <label class="control-label" for="input-email"><?php echo $entry_email; ?></label>
                                        <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" required autocomplete="email" />
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="input-password"><?php echo $entry_password; ?></label>
                                        <input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />
                                        <a target="_blank" href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a>
                                    </div>
                                    <a onclick="<?php echo $login_action; ?>" class="btn btn-primary"><?php echo $button_login; ?></a>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    apiBaseUrl = '<?php echo $apiBaseUrl; ?>';
    baseUrl = '<?php echo $base_url; ?>';
    apps_version = '<?php echo VERSION; ?>';
    token = '<?php echo $token; ?>';
    <?php if ($error_warning || $data['changeApiKey']) { ?>
        error = true;
        <?php } else { ?>
        error = false;
        <?php } ?>

    $(document).ready(function() {
        if (!Marketplace.apps.loaded && !error) {
            url_data = <?php echo json_encode($url_data); ?>;
            Marketplace.apps.initialize(url_data);
            checkMenu();
        }
    });
    function checkMenu() {
        if (Marketplace.apps.loaded && !error) {
            if($('#marketplace-header').is(':visible')) {
                if (sessionStorage.getItem('marketplace-menu')) {
                    // Open the last visited page.
                    Marketplace.loadweb(window.location.origin + window.location.pathname + '?' + sessionStorage.getItem('marketplace-menu'));
                }
            } else {
                setTimeout(checkMenu, 50);
            }
        }
    }

</script>

<?php echo $footer; ?>
