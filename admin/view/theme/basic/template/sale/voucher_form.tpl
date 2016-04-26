<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <?php if ($voucher_id) { ?>
                <button type="button" id="button-send" data-toggle="tooltip" title="<?php echo $button_send; ?>" class="btn btn-default"><i class="fa fa-envelope"></i></button>
                <?php } ?>
                <button type="submit" form="form-voucher" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success"><i class="fa fa-check"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-times-circle text-danger"></i></a>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-voucher" class="form-horizontal">
            <div class="row">
                <div class="left-col col-sm-8">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="general">
                                <div class="form-group required">
                                    <label class="col-sm-12" for="input-code"><span data-toggle="tooltip" title="<?php echo $help_code; ?>"><?php echo $entry_code; ?></span></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="code" value="<?php echo $code; ?>" placeholder="<?php echo $entry_code; ?>" id="input-code" class="form-control" />
                                        <?php if ($error_code) { ?>
                                        <div class="text-danger"><?php echo $error_code; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-12" for="input-from-name"><?php echo $entry_from_name; ?></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="from_name" value="<?php echo $from_name; ?>" placeholder="<?php echo $entry_from_name; ?>" id="input-from-name" class="form-control" />
                                        <?php if ($error_from_name) { ?>
                                        <div class="text-danger"><?php echo $error_from_name; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-12" for="input-from-email"><?php echo $entry_from_email; ?></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="from_email" value="<?php echo $from_email; ?>" placeholder="<?php echo $entry_from_email; ?>" id="input-from-email" class="form-control" />
                                        <?php if ($error_from_email) { ?>
                                        <div class="text-danger"><?php echo $error_from_email; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-12" for="input-to-name"><?php echo $entry_to_name; ?></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="to_name" value="<?php echo $to_name; ?>" placeholder="<?php echo $entry_to_name; ?>" id="input-to-name" class="form-control" />
                                        <?php if ($error_to_name) { ?>
                                        <div class="text-danger"><?php echo $error_to_name; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-12" for="input-to-email"><?php echo $entry_to_email; ?></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="to_email" value="<?php echo $to_email; ?>" placeholder="<?php echo $entry_to_email; ?>" id="input-to-email" class="form-control" />
                                        <?php if ($error_to_email) { ?>
                                        <div class="text-danger"><?php echo $error_to_email; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-theme"><?php echo $entry_theme; ?></label>
                                    <div class="col-sm-12">
                                        <select name="voucher_theme_id" id="input-theme" class="form-control">
                                            <?php foreach ($voucher_themes as $voucher_theme) { ?>
                                            <?php if ($voucher_theme['voucher_theme_id'] == $voucher_theme_id) { ?>
                                            <option value="<?php echo $voucher_theme['voucher_theme_id']; ?>" selected="selected"><?php echo $voucher_theme['name']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $voucher_theme['voucher_theme_id']; ?>"><?php echo $voucher_theme['name']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-12" for="input-message"><?php echo $entry_message; ?></label>
                                    <div class="col-sm-12">
                                        <textarea name="message" rows="5" placeholder="<?php echo $entry_message; ?>" id="input-message" class="form-control"><?php echo $message; ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-amount"><?php echo $entry_amount; ?></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="amount" value="<?php echo $amount; ?>" placeholder="<?php echo $entry_amount; ?>" id="input-amount" class="form-control" />
                                        <?php if ($error_amount) { ?>
                                        <div class="text-danger"><?php echo $error_amount; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if ($voucher_id) { ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><?php echo $tab_history; ?></h3>
                            <div class="pull-right">
                                <div class="panel-chevron"><i class="fa fa-chevron-up rotate-reset"></i></div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="history">
                                <div id="history"></div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <div class="right-col col-sm-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><?php echo $text_publish; ?></h3>
                            <div class="pull-right">
                                <div class="panel-chevron"><i class="fa fa-chevron-up rotate-reset"></i></div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="publish">
                                <div class="form-group">
                                    <label class="col-sm-12"><?php echo $text_enabled; ?></label>
                                    <div class="col-sm-12">
                                        <label class="radio-inline">
                                            <?php if ($status) { ?>
                                            <input type="radio" name="status" value="1" checked="checked" />
                                            <?php echo $text_enabled; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="status" value="1" />
                                            <?php echo $text_enabled; ?>
                                            <?php } ?>
                                        </label>
                                        <label class="radio-inline">
                                            <?php if (!$status) { ?>
                                            <input type="radio" name="status" value="0" checked="checked" />
                                            <?php echo $text_disabled; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="status" value="0" />
                                            <?php echo $text_disabled; ?>
                                            <?php } ?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <?php if ($voucher_id) { ?>
    <script type="text/javascript"><!--
    $('#button-send').on('click', function() {
        $.ajax({
            url: 'index.php?route=sale/voucher/send&token=<?php echo $token; ?>',
            type: 'post',
            dataType: 'json',
            data: 'voucher_id=<?php echo $voucher_id; ?>',
            beforeSend: function() {
                $('#button-send i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
                $('#button-send').prop('disabled', true);
            },
            complete: function() {
                $('#button-send i').replaceWith('<i class="fa fa-envelope"></i>');
                $('#button-send').prop('disabled', false);
            },
            success: function(json) {
                $('.alert').remove();

                if (json['error']) {
                    $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
                }

                if (json['success']) {
                    $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i>  ' + json['success'] + '</div>');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    })
    //--></script>
    <script type="text/javascript"><!--
    $('#history').delegate('.pagination a', 'click', function(e) {
        e.preventDefault();

        $('#history').load(this.href);
    });

    $('#history').load('index.php?route=sale/voucher/history&token=<?php echo $token; ?>&voucher_id=<?php echo $voucher_id; ?>');
    //--></script>
    <?php } ?>
</div>
<?php echo $footer; ?>
