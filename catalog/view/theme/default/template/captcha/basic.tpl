<div class="form-group required">
    <label class="col-sm-2 control-label" for="input-basic-captcha"><?php echo $entry_captcha; ?></label>
    <div class="col-sm-10">
        <input type="text" name="basic_captcha_phrase" id="input-basic-captcha" class="input-mini" style="height: 34px; padding: 6px 12px;" />
        <img src="<?php echo $captcha; ?>" />
        <?php if ($error_captcha) { ?>
        <div class="text-danger"><?php echo $error_captcha; ?></div>
        <?php } ?>
    </div>
</div>