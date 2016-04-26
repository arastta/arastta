<p><?php echo $text_i_am_returning_customer; ?></p>
<div class="form-group required">
    <label class="control-label" for="input-login-email"><?php echo $entry_email; ?></label>
    <input type="text" name="email" value="" placeholder="<?php echo $entry_email; ?>" id="input-login-email" class="form-control" required autocomplete="email" />
</div>
<div class="form-group required">
    <label class="control-label" for="input-payment-telephone"><?php echo $entry_password; ?></label>
    <input type="password" name="password" value="" placeholder="<?php echo $entry_password; ?>" id="input-login-password" class="form-control" />
</div>

<input type="button" class="btn btn-primary" id="button-login" value="<?php echo $button_login; ?>">

<a href="<?php echo $forgotten; ?>" title="<?php echo $text_forgotten; ?>" class="login-forgotten-link"><?php echo $text_forgotten; ?></a>

<script type="text/javascript"><!--
$('#ar-right-1 input').keydown(function(e) {
    if (e.keyCode == 13) {
        $('#button-login').click();
    }
});
//--></script>
