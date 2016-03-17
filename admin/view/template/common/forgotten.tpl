<?php echo $header; ?>
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
                            <?php if ($error_warning) { ?>
                            <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                            </div>
                            <?php } ?>
                            <form action="<?php echo $action; ?>" method="post" class="login-form" enctype="multipart/form-data">
                                <h4 class="form-title"><?php echo $heading_title; ?></h4>
                                <br />
                                <div class="form-group">
                                    <label for="input-email"><?php echo $entry_email; ?></label>
                                    <div class="input-icon">
                                        <i class="fa fa-envelope"></i>
                                        <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
                                    </div>
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> <?php echo $button_reset; ?></button>
                                    <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright">
        <a href="<?php echo $store['href']; ?>"> ← Back to <?php echo $store['name']; ?></a>
    </div>
</div>
<script type="text/javascript">
    // set focus on email
    $('input[name=email]').focus();
</script>
<?php echo $footer; ?>
