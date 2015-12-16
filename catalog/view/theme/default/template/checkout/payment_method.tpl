<?php if ($error_warning) { ?>
<div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($payment_methods) { ?>
<p><?php echo $text_payment_method; ?></p>
<?php foreach ($payment_methods as $payment_method) { ?>
<div class="radio">
    <label>
        <?php if ($payment_method['code'] == $code || !$code) { ?>
        <?php $code = $payment_method['code']; ?>
        <input type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" checked="checked" />
        <?php } else { ?>
        <input type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" />
        <?php } ?>
        <?php echo $payment_method['title']; ?>
        <?php if ($payment_method['terms']) { ?>
        (<?php echo $payment_method['terms']; ?>)
        <?php } ?>
    </label>
</div>
<?php } ?>
<?php } ?>

<script type="text/javascript"><!--
$(document).on('change', 'input[name=\'payment_method\']:checked', function () {
    savePaymentMethod();
});
//--></script>
